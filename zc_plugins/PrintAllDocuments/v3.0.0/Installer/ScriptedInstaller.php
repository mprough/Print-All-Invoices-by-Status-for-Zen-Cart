<?php

use Zencart\PluginSupport\ScriptedInstaller as ScriptedInstallBase;

class ScriptedInstaller extends ScriptedInstallBase
{
    protected function executeInstall()
    {
        zen_deregister_admin_pages(['reportsPrintAllDocuments', 'reportsAllInvoices', 'reportsAllPackingSlips']);
        zen_register_admin_page(
            'reportsPrintAllDocuments',
            'BOX_REPORTS_PRINT_ALL_DOCUMENTS',
            'FILENAME_PRINT_ALL_DOCUMENTS',
            '',
            'reports',
            'Y',
            20
        );

        parent::executeInstall();
        return true;
    }

    protected function executeUninstall()
    {
        zen_deregister_admin_pages(['reportsPrintAllDocuments', 'reportsAllInvoices', 'reportsAllPackingSlips']);
        parent::executeUninstall();
        return true;
    }
}
