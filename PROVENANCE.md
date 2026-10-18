# Font provenance

- `fonts/NotoSansJP-Regular.ttf`: 5,766,828 bytes; SHA-256 `a66655d7b24ac6f793f73914c60ad44522b9fbd9e5f9b3706220b66c44fdaad2`.
- `fonts/NotoSerifJP-Regular.ttf`: 8,079,912 bytes; SHA-256 `78259fd13039dc0a18a76a6f6f9ce2554c271c27626021e1b0a4b7235401ec61`.
- `fonts/OFL-Serif.txt`: 4,350 bytes; SHA-256 `5e0da210fb04058a8c0087985d2d456b931c2579811a49655721d3cf0c36b6d6`.
- `fonts/OFL.txt`: 4,388 bytes; SHA-256 `1c05c68c34f9708415aada51f17e1b0092d2cea709bf4a94cd38114f9e73d7d9`.

The font binaries are copied byte for byte from vicam/papyrus-pdf-fonts-cjk v0.3.0 (commit `f998e99`). Original provenance follows; references to other regions describe the source package, not additional bundled fonts.

# Font provenance

| File | Bytes | SHA-256 |
| --- | --- | --- |
| `fonts/NotoSansSC-Regular.ttf` | 10,595,876 | `9cdb15629d85f80bc058033b0b3b5444d63f69e976e54a9ae4c2d6f37f96c565` |
| `fonts/NotoSansKR-Regular.ttf` | 6,224,812 | `adaf517dd462c1ad9da1e115c36975b4deb579aa1ad71364ad9f235a073b8398` |
| `fonts/OFL.txt` | 4,388 | `1c05c68c34f9708415aada51f17e1b0092d2cea709bf4a94cd38114f9e73d7d9` |

Both are static TrueType (`glyf`) instances at weight 400, with `OS/2.fsType` 0 (installable embedding, subsetting allowed). The Vicam PDF overlay refuses variable fonts, and Google Fonts publishes these families only as variable fonts, so the static instances were made from them:

- Origin: <https://github.com/google/fonts> at commit `66928797f271fed5ee428544e7229685e2d90127`:
  - `ofl/notosanssc/NotoSansSC[wght].ttf`, SHA-256 `a3041811a78c361b1de50f953c805e0244951c21c5bd412f7232ef0d899af0da`;
  - `ofl/notosanskr/NotoSansKR[wght].ttf`, SHA-256 `194018e6b2b293a7964f037b25c0249ce1418bc9ab3c971060a03aa57861e252`;
  - `ofl/notosanssc/OFL.txt`, identical to `ofl/notosanskr/OFL.txt`, copied unmodified as `fonts/OFL.txt`.
- Instancing (a development step, not run by the package): `fonttools varLib.instancer 'NotoSansSC[wght].ttf' wght=400 --update-name-table`, and the same for KR, with fontTools 4.66.1.
- Check (2026-10-13): the variable sources were downloaded again at that commit and matched the hashes above. Re-instancing them gives files of the same size whose tables are byte-identical except `head.modified` and the `head.checkSumAdjustment` derived from it, so the committed files are those instances.

The upstream fonts are Noto Sans CJK, derived from Source Han Sans: "Copyright 2014-2021 Adobe (http://www.adobe.com/), with Reserved Font Name 'Source'". Instancing changes the font program, so these are Modified Versions under the OFL. They keep the upstream family names (`Noto Sans SC`, `Noto Sans KR`), which are not Reserved Font Names; the only Reserved Font Name, "Source", is not used. Google Fonts distributes the variable fonts under these names.

## JP and serif additions (lane J)

Official Google Fonts repository, commit `47d54ca1ebb14fd864695f1d39dc734f39017c33`.
All four additions are static TrueType/glyf Regular instances at `wght=400`, made
with FontTools 4.66.1 `varLib.instancer` (`updateFontNames=True`,
`recalcTimestamp=False`). Their `OS/2.fsType` is 0: embedding and subsetting are
permitted. Variable axes and `fvar` are removed. This build step is not a runtime
dependency. No source code was copied. Vicam PDF supports CFF subsetting too;
these glyf instances use the already tested overlay and form subset path.

- `NotoSansJP-Regular.ttf`: 5,766,828 bytes, SHA-256 `a66655d7b24ac6f793f73914c60ad44522b9fbd9e5f9b3706220b66c44fdaad2`.
  Version `Version 2.004-H2;hotconv 1.0.118;makeotfexe 2.5.65603`.
  Source: <https://raw.githubusercontent.com/google/fonts/47d54ca1ebb14fd864695f1d39dc734f39017c33/ofl/notosansjp/NotoSansJP%5Bwght%5D.ttf>; SHA-256 `c2f3b4d463500a2ddcd3849cded1fceeb9fd6d1c32e6cbecd568453ba50fc68f`.
- `NotoSerifJP-Regular.ttf`: 8,079,912 bytes, SHA-256 `78259fd13039dc0a18a76a6f6f9ce2554c271c27626021e1b0a4b7235401ec61`.
  Version `Version 2.003-H1;hotconv 1.1.1;makeotfexe 2.6.0`.
  Source: <https://raw.githubusercontent.com/google/fonts/47d54ca1ebb14fd864695f1d39dc734f39017c33/ofl/notoserifjp/NotoSerifJP%5Bwght%5D.ttf>; SHA-256 `2fd527ba12b6a44ec30d796d633360da0aeba6c5d4af1304ce12bb4dc15a7dfc`.
- `NotoSerifSC-Regular.ttf`: 14,854,656 bytes, SHA-256 `04e8390ae48501976db2bc8228e9819dd0798a2dacf29f8e8d0b58a17d514d85`.
  Version `Version 2.003-H1;hotconv 1.1.1;makeotfexe 2.6.0`.
  Source: <https://raw.githubusercontent.com/google/fonts/47d54ca1ebb14fd864695f1d39dc734f39017c33/ofl/notoserifsc/NotoSerifSC%5Bwght%5D.ttf>; SHA-256 `050080d9255a86808f2945bffac582b31ef32bc36411ce29563b4961670c66f9`.
- `NotoSerifKR-Regular.ttf`: 14,122,700 bytes, SHA-256 `21e2cd0ff88833ef416fd10943de9c7f06315ef6c0465555b48838b8a24470fb`.
  Version `Version 2.003-H1;hotconv 1.1.1;makeotfexe 2.6.0`.
  Source: <https://raw.githubusercontent.com/google/fonts/47d54ca1ebb14fd864695f1d39dc734f39017c33/ofl/notoserifkr/NotoSerifKR%5Bwght%5D.ttf>; SHA-256 `11f8d5de6f1b79195efba3828aaa2ec95c1178f5ae976fb23c8d53250a9938f3`.

SIL OFL 1.1: `fonts/OFL.txt` remains the original Sans licence (JP's source is
byte-identical); `fonts/OFL-Serif.txt` is the unmodified Serif licence. SC and KR
Serif licences are byte-identical to JP's. Serif copyright is 2017–2024 Adobe;
Sans copyright and the reserved name “Source” are recorded above. Static instances
are Modified Versions; Noto family names are not reserved names.

- `OFL.txt`: <https://raw.githubusercontent.com/google/fonts/47d54ca1ebb14fd864695f1d39dc734f39017c33/ofl/notosansjp/OFL.txt>; SHA-256 `1c05c68c34f9708415aada51f17e1b0092d2cea709bf4a94cd38114f9e73d7d9`.
- `OFL-Serif.txt`: <https://raw.githubusercontent.com/google/fonts/47d54ca1ebb14fd864695f1d39dc734f39017c33/ofl/notoserifjp/OFL.txt>; SHA-256 `5e0da210fb04058a8c0087985d2d456b931c2579811a49655721d3cf0c36b6d6`.
