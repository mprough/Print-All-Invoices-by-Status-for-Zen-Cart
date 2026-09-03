# Print all invoices and packing slips by status for Zen Cart

Print every invoice or packing slip for orders with a selected order status in one printable batch. Each document begins on a separate printed page. The current release is **v3.0.6**.

**Official Zen Cart plugin page:** [All Invoices](https://www.zen-cart.com/plugins/all-invoices-vb1083)

Version 3 uses the store's own native Zen Cart invoice and packing-slip pages. That preserves the current Zen Cart layout, product images, tax display, notifier output, and compatible site-specific customizations instead of maintaining outdated copies of those documents inside this plugin.

## Features

- Prints invoices or packing slips by order status.
- Combines the selected documents into one browser print job.
- Shows loading progress and reports individual orders that could not be loaded.
- Loads documents sequentially to avoid a burst of simultaneous admin requests.
- Does not change order statuses or modify order data.
- Installs through the Zen Cart Plugin Manager.
- Makes no core-file changes.

## Requirements

- Zen Cart 2.0.1 through 2.2.2
- PHP 8.0 through 8.5, within the range supported by the installed Zen Cart version
- JavaScript enabled in the admin browser

## Installation

1. Download the repository ZIP and extract it.
2. Copy the `zc_plugins` directory into the root of the Zen Cart installation.
3. In the Zen Cart admin, open **Modules > Plugin Manager**.
4. Locate **Print all invoices and packing slips by status** and select **Install**.

The plugin adds **Reports > Print invoices and packing slips**.

## Use

1. Open **Reports > Print invoices and packing slips**.
2. Select an order status.
3. Select **Invoices** or **Packing slips**.
4. Select **Generate printable batch**.
5. Wait for the batch to finish loading, then select **Print batch**.

The printable batch opens in a separate tab. A failed individual order is reported on screen and excluded from the printed output so one failure does not discard the remainder of the batch.

## Upgrade from v2.x

Remove the old manually installed `all_invoices.php`, `all_packingslips.php`, associated templates, language files, extra-datafile, and legacy auto-installer after making a backup. Then install v3 through Plugin Manager. The v3 installer removes the two obsolete v2.x Reports menu registrations; it does not delete old files automatically.

Legacy files to remove from the renamed admin directory:

- `all_invoices.php`
- `all_packingslips.php`
- `includes/extra_datafiles/all_invoices_filenames.php`
- `includes/functions/extra_functions/init_all_invoices.php`
- `includes/languages/english/all_invoices.php`
- `includes/languages/english/all_packingslips.php`
- `includes/languages/english/extra_definitions/all_invoices_definitions.php`
- `includes/templates/tpl_all_invoices.php`
- `includes/templates/tpl_all_packingslips.php`

Version 3 deliberately removes v2.5's hardcoded **Change Status to 2-Processed** action. Printing documents should not silently alter order workflow, and status ID 2 is not guaranteed to represent the same business state on every store.

## Uninstallation

Use **Modules > Plugin Manager** to uninstall the plugin, then remove its version directory from `zc_plugins/PrintAllDocuments`. Uninstallation removes the plugin's Reports menu entry and does not alter orders.

## Compatibility note

The plugin assembles the native `invoice.php` and `packingslip.php` output from the installed Zen Cart admin. Customizations that appear on those individual pages should also appear in the batch when they produce normal HTML inside the document container.

## Support

- Bug reports: [PRO-Webs support](https://prowebsinc.zohodesk.com/portal/en/newticket)
- Official distribution page: [Zen Cart - All Invoices](https://www.zen-cart.com/plugins/all-invoices-vb1083)

This software is provided without warranty. Installation and customization are not included. Custom work is available at the current hourly rate.

## Credits

- Mathew O'Marah, mdo design: original All Invoices plugin, v1.0 and v1.1
- Cindy Merkin (lat9), Vinos de Frutas Tropicales: Zen Cart 1.5 updates and packing-slip support
- DrByte: v2.5 order-status update feature
- Melanie Prough, [PRO-Webs.net](https://pro-webs.net/): v2.6 maintenance and v3 redesign

## License

Copyright remains with the respective contributors. This plugin is free software distributed under the [GNU General Public License v2.0](LICENSE). You may use, modify, and redistribute it under that license. It is provided without warranty.
