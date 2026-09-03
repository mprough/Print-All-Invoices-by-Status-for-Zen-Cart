# Changelog

## v3.0.6 - 2026-09-03

- Registers the document compatibility init script with Zen Cart's admin autoloader so the v3.0.4-v3.0.5 targeted deprecation handler actually runs.

## v3.0.5 - 2026-09-03

- Extends the narrowly scoped Zen Cart separator-deprecation handler to native invoice batch requests as well as packing-slip batch requests.

## v3.0.4 - 2026-09-03

- Prevents Zen Cart's native packing-slip separator from logging its PHP 8.4+ `str_contains(): Passing null` deprecation during batch generation.
- The compatibility handler is limited to marked packing-slip batch requests and forwards every unrelated error to Zen Cart's existing error handler.

## v3.0.3 - 2026-09-03

- Preserves the Zen Cart admin router's `cmd=print_all_documents` parameter when the GET form is submitted.
- Fixes generated batch requests falling back to the admin home page as `index.php?generate=1...`.
- Distribution now contains only the current Plugin Manager version so obsolete malformed language files cannot be loaded merely by selecting the plugin.

## v3.0.2 - 2026-09-03

- Fixed the Plugin Manager extra-definitions language file to return an array as required by Zen Cart's `ArraysLanguageLoader`.
- Prevents the HTTP 500 and `loadArrayDefineFile(): Return value must be of type array, int returned` fatal error immediately after selecting the plugin.

## v3.0.1 - 2026-09-03

- Hardened the Plugin Manager scripted installer after the initially reported HTTP 500.
- Added the proven install, upgrade, and uninstall method contracts and direct menu registration pattern.
- The remaining selection-time fatal error was subsequently identified and corrected in v3.0.2.

## v3.0.0 - 2026-09-03

- Added batch printing for both invoices and packing slips from one Reports menu page.
- Rebuilt the plugin for the Zen Cart Plugin Manager.
- Uses the installed store's native invoice and packing-slip pages so current Zen Cart output, notifiers, images, taxes, and compatible customizations are retained.
- Loads orders sequentially to avoid a burst of simultaneous admin requests.
- Added progress, error reporting, print styling, empty-status handling, and admin-session detection.
- Removed the legacy hardcoded action that changed every selected order to status ID 2.
- Added Zen Cart 2.0.1 through 2.2.2 and PHP 8 compatibility.

## v2.5 - 2016-03-16

- Added the ability to update the status of displayed invoice orders. Contributed by DrByte.

## v2.6 - 2019-09-12

- Corrected a PHP 7.3 error for Zen Cart 1.5.6c. Maintained by PRO-Webs.net.

## v2.1.0 - 2014-04-03

- Added printing of all packing slips for a selected order status.
- Corrected Chrome page breaks in the invoice template.

## v2.0.0 - 2013-05-19

- Updated the original All Invoices plugin for Zen Cart 1.5.0.
- Moved interface text into language files.
- Added a clear message when no orders match the selected status.

## v1.1 - 2010-04-12

- Corrected the product-name display.

## v1.0 - 2010-03-19

- Initial release by Mathew O'Marah of mdo design.
