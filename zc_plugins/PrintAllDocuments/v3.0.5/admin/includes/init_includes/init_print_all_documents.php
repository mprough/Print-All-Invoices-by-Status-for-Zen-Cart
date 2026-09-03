<?php
/**
 * Suppress a narrowly scoped Zen Cart document deprecation during batch generation.
 *
 * Zen Cart 2.2.x passes a null image width from zen_draw_separator() to zen_image()
 * on its native invoice and packing-slip pages. PHP 8.4+ reports that core behavior when
 * str_contains() receives the null value. Other errors continue to use Zen Cart's
 * previously registered error handler.
 */

if (
    isset($_GET['print_all_documents'])
    && $_GET['print_all_documents'] === '1'
    && isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower((string)$_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    && in_array(basename((string)($_SERVER['SCRIPT_NAME'] ?? '')), ['invoice.php', 'packingslip.php'], true)
) {
    $printAllDocumentsPreviousErrorHandler = null;
    $printAllDocumentsPreviousErrorHandler = set_error_handler(
        static function (int $errorNumber, string $errorMessage, string $errorFile, int $errorLine) use (&$printAllDocumentsPreviousErrorHandler): bool {
            if (
                $errorNumber === E_DEPRECATED
                && basename($errorFile) === 'html_output.php'
                && in_array($errorLine, [150, 155], true)
                && str_contains($errorMessage, 'str_contains(): Passing null')
            ) {
                return true;
            }

            if (is_callable($printAllDocumentsPreviousErrorHandler)) {
                return (bool)$printAllDocumentsPreviousErrorHandler($errorNumber, $errorMessage, $errorFile, $errorLine);
            }

            return false;
        }
    );
}
