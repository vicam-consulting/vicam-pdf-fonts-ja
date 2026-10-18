<?php

declare(strict_types=1);

namespace PapyrusPDF\FontsJa;

use PapyrusPDF\Forms\{FormFontFallbacks, FormFontPackage};

/** Static Regular Japanese fonts; files are read lazily and subset-embedded. */
final class NotoJa implements FormFontPackage
{
    public const string SANS = 'NotoSansJP-Regular.ttf';
    public const string SERIF = 'NotoSerifJP-Regular.ttf';

    public static function path(string $file): string
    {
        if (!in_array($file, [self::SANS, self::SERIF], true)) {
            throw new \InvalidArgumentException('Unknown JP font file.');
        }

        return dirname(__DIR__).'/fonts/'.$file;
    }

    public static function formFallbacks(): FormFontFallbacks
    {
        return new FormFontFallbacks(
            japaneseSans: self::path(self::SANS),
            japaneseSerif: self::path(self::SERIF),
        );
    }
}
