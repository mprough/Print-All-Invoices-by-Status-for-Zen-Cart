# Changelog

## v3.0.3 - 2026-09-03

- Preserves the Zen Cart admin router's `cmd=print_all_documents` parameter when the GET form is submitted.
- Fixes generated batch requests falling back to the admin home page as `index.php?generate=1...`.
- Distribution now contains only the current Plugin Manager version so obsolete malformed language files cannot be loaded merely by selecting the plugin.

## v3.0.2 - 2026-09-03

- Fixed the Plugin Manager extra-definitions language file to return an array as required by Zen Cart's `ArraysLanguageLoader`.
- Prevents the HTTP 500 and `loadArrayDefineFile(): Return value must be of type array, int returned` fatal error immediately after selecting the plugin.

## v3.0.1 - 2026-09-03

- Corrected the Plugin Manager scripted installer that produced an HTTP 500 during installation.
- Uses the proven install, upgrade, and uninstall method contracts and direct menu registration pattern.

## v3.0.0 - 2026-09-03

- Added batch printing for both invoices and packing slips from one Reports menu page.
- Rebuilt the plugin for the Zen Cart Plugin Manager.
- Uses the installed store's native invoice and packing-slip pages so current Zen Cart output, notifiers, images, taxes, and compatible customizations are retained.
- Loads orders sequentially to avoid a burst of simultaneous admin requests.
- Added progress, error reporting, print styling, empty-status handling, and admin-session detection.
- Removed the legacy hardcoded action that changed every selected order to status ID 2.
- Added Zen Cart 2.0.1 through 2.2.2 and PHP 8 compatibility.

## v2.5 - 2016-03-16

- Added the ability to update the status of displayed invoice orders.

## v2.1 - 2014

- Added printing of all packing slips for a selected order status.

## v2.0.0 - 2012-05-18

- Updated the original All Invoices plugin for Zen Cart 1.5.0.
