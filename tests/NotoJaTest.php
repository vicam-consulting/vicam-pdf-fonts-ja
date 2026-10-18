<?php

declare(strict_types=1);

namespace PapyrusPDF\FontsJa\Tests;

use PapyrusPDF\FontsJa\NotoJa;
use PapyrusPDF\Forms\{FieldAssignment, FieldValue, FormFillRequest};
use PapyrusPDF\PdfEngineFactory;
use PHPUnit\Framework\TestCase;

final class NotoJaTest extends TestCase
{
    private const array HASHES = [
        'NotoSansJP-Regular.ttf' => 'a66655d7b24ac6f793f73914c60ad44522b9fbd9e5f9b3706220b66c44fdaad2',
        'NotoSerifJP-Regular.ttf' => '78259fd13039dc0a18a76a6f6f9ce2554c271c27626021e1b0a4b7235401ec61',
    ];

    public function testManifestAndStaticRegularFontsMatchProvenance(): void
    {
        $files = array_filter(get_object_vars(NotoJa::formFallbacks()));
        self::assertCount(2, $files);
        $directory = sys_get_temp_dir().'/papyrus-cjk-'.bin2hex(random_bytes(6));
        self::assertTrue(mkdir($directory, 0700));
        try {
            $engine = (new PdfEngineFactory())->create($directory);
            foreach ($files as $file) {
                self::assertIsString($file);
                self::assertFileExists($file);
                self::assertSame([\PapyrusPDF\Forms\FormFontPackage::class], array_values(class_implements(NotoJa::class)));
                if (isset(self::HASHES[basename($file)])) {
                    self::assertSame(self::HASHES[basename($file)], hash_file('sha256', $file));
                }
                $bytes = file_get_contents($file);
                self::assertIsString($bytes);
                $font = $engine->readFont($bytes);
                self::assertTrue($font->embedding->subsettingAllowed);
                self::assertStringEndsWith('-Regular', $font->postScriptName);
                foreach (['A', '東', '京'] as $character) {
                    self::assertNotNull($font->glyphFor(mb_ord($character)));
                }
            }
        } finally {
            rmdir($directory);
        }
    }

    public function testInstalledPackageIsDiscoveredAndFormAppearanceIsSmall(): void
    {
        $directory = sys_get_temp_dir().'/papyrus-cjk-form-'.bin2hex(random_bytes(6));
        self::assertTrue(mkdir($directory, 0700));
        try {
            $path = $directory.'/source.pdf';
            self::assertNotFalse(file_put_contents($path, $this->source()));
            $request = new FormFillRequest([new FieldAssignment('name', FieldValue::text('東京都ひらがな'))]);
            $engine = (new PdfEngineFactory())->create($directory);
            $result = $engine->fillForm($path, $request);
            self::assertContains('form.font-substituted', $result->report->warnings);
            self::assertSame('NotoSerifJP-Regular', $result->report->fontSubstitutions[0]->substitutedFace);
            $result->artifact->publishTo($directory.'/filled.pdf');
            self::assertLessThan(100_000, filesize($directory.'/filled.pdf'));
            self::assertSame('name', $engine->inspectForm($directory.'/filled.pdf')->fields[0]->name->fullyQualified);
            $disabled = (new PdfEngineFactory(discoverFormFonts: false))->create($directory);
            try {
                $disabled->fillForm($path, $request)->artifact->close();
                self::fail('Without discovery a Japanese program is unavailable.');
            } catch (\PapyrusPDF\Core\PdfException $error) {
                // PapyrusPDF 0.3.6 reports the uncovered value; earlier releases report the unusable field font.
                self::assertContains($error->diagnostic, ['form.glyph-unavailable', 'form.font-unsupported']);
            }
        } finally {
            foreach (glob($directory.'/*') ?: [] as $file) {
                unlink($file);
            }
            rmdir($directory);
        }
    }

    public function testUnknownFilesAreRefused(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        NotoJa::path('../font.ttf');
    }

    /** Original blank synthetic form, no corpus or third-party PDF bytes. */
    private function source(): string
    {
        $objects = [
            '<< /Type /Catalog /Pages 2 0 R /AcroForm 3 0 R >>',
            '<< /Type /Pages /Kids [4 0 R] /Count 1 >>',
            '<< /Fields [6 0 R] /DA (/Mincho 12 Tf 0 g) /DR << /Font << /Mincho 5 0 R >> >> >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 300 300] /Resources << >> /Annots [6 0 R] >>',
            '<< /Type /Font /Subtype /Type0 /BaseFont /MS-Mincho /Encoding /UniJIS-UCS2-H /DescendantFonts [] >>',
            '<< /Type /Annot /Subtype /Widget /FT /Tx /T (name) /Rect [20 230 280 260] /F 4 /P 4 0 R >>',
        ];
        $bytes = "%PDF-1.7\n";
        $offsets = [];
        foreach ($objects as $index => $object) {
            $offsets[] = strlen($bytes);
            $bytes .= ($index + 1)." 0 obj\n".$object."\nendobj\n";
        }
        $xref = strlen($bytes);
        $bytes .= "xref\n0 7\n0000000000 65535 f \n";
        foreach ($offsets as $offset) {
            $bytes .= sprintf("%010d 00000 n \n", $offset);
        }

        return $bytes."trailer\n<< /Size 7 /Root 1 0 R >>\nstartxref\n".$xref."\n%%EOF\n";
    }
}
