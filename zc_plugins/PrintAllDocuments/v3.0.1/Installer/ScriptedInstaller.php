<?php

declare(strict_types=1);

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

use Zencart\PluginSupport\ScriptedInstaller as ScriptedInstallBase;

class ScriptedInstaller extends ScriptedInstallBase
{
    protected function executeInstall(): bool
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

        return true;
    }

    protected function executeUpgrade(...$args): bool
    {
        return $this->executeInstall();
    }

    protected function executeUninstall(): bool
    {
        zen_deregister_admin_pages(['reportsPrintAllDocuments', 'reportsAllInvoices', 'reportsAllPackingSlips']);
        return true;
    }
}
