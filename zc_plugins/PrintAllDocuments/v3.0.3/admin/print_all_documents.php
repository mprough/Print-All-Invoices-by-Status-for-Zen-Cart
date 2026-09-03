<?php
/**
 * Print all invoices and packing slips by status
 *
 * @copyright Copyright 2026 PRO-Webs, Inc.
 * @copyright Portions Copyright 2003-2026 Zen Cart Development Team
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2.0
 */

require 'includes/application_top.php';

$documentTypes = [
    'invoice' => [
        'label' => TEXT_INVOICES,
        'endpoint' => 'invoice.php',
        'singular' => 'invoice',
        'plural' => 'invoices',
    ],
    'packingslip' => [
        'label' => TEXT_PACKING_SLIPS,
        'endpoint' => 'packingslip.php',
        'singular' => 'packing slip',
        'plural' => 'packing slips',
    ],
];

$statuses = zen_getOrdersStatuses();
$statusOptions = $statuses['orders_statuses'];
$validStatusIds = array_map('intval', array_keys($statuses['orders_status_array']));
$selectedStatus = isset($_GET['status']) ? (int)$_GET['status'] : 0;
$selectedType = isset($_GET['document_type']) ? (string)$_GET['document_type'] : 'invoice';
$generate = isset($_GET['generate']) && $_GET['generate'] === '1';
$orderIds = [];
$requestError = '';

if ($generate) {
    if (!isset($documentTypes[$selectedType]) || !in_array($selectedStatus, $validStatusIds, true)) {
        $requestError = ERROR_INVALID_REQUEST;
    } else {
        $orders = $db->Execute(
            'SELECT orders_id FROM ' . TABLE_ORDERS .
            ' WHERE orders_status = ' . $selectedStatus .
            ' ORDER BY orders_id'
        );
        foreach ($orders as $orderRow) {
            $orderIds[] = (int)$orderRow['orders_id'];
        }
    }
}
?>
<!doctype html>
<html <?= HTML_PARAMS ?>>
<head>
    <?php require DIR_WS_INCLUDES . 'admin_html_head.php'; ?>
    <style>
        .print-all-actions { margin: 1.5rem 0; }
        .print-all-actions .btn { margin-right: .5rem; }
        .print-all-progress { margin: 2rem auto; max-width: 52rem; }
        .batch-document { break-after: page; page-break-after: always; }
        .batch-document:last-child { break-after: auto; page-break-after: auto; }
        .batch-error { margin: 1rem; }
        @media print {
            .print-all-controls, .batch-error { display: none !important; }
            .batch-document { margin: 0; }
        }
    </style>
</head>
<body>
<?php if (!$generate) { ?>
    <?php require DIR_WS_INCLUDES . 'header.php'; ?>
    <div class="container-fluid">
        <h1><?= HEADING_TITLE ?></h1>
        <p><?= TEXT_INTRODUCTION ?></p>
        <form method="get" action="index.php" target="_blank" class="form-horizontal">
            <?= zen_draw_hidden_field('cmd', FILENAME_PRINT_ALL_DOCUMENTS) ?>
            <?= zen_draw_hidden_field('generate', '1') ?>
            <div class="form-group">
                <label class="control-label col-sm-2" for="status"><?= TEXT_ORDER_STATUS ?></label>
                <div class="col-sm-6"><?= zen_draw_pull_down_menu('status', $statusOptions, '', 'id="status" class="form-control" required') ?></div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="document_type"><?= TEXT_DOCUMENT_TYPE ?></label>
                <div class="col-sm-6">
                    <?= zen_draw_pull_down_menu('document_type', [
                        ['id' => 'invoice', 'text' => TEXT_INVOICES],
                        ['id' => 'packingslip', 'text' => TEXT_PACKING_SLIPS],
                    ], 'invoice', 'id="document_type" class="form-control"') ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-6">
                    <button type="submit" class="btn btn-primary"><?= TEXT_GENERATE ?></button>
                </div>
            </div>
        </form>
    </div>
    <?php require DIR_WS_INCLUDES . 'footer.php'; ?>
<?php } else { ?>
    <div class="container-fluid print-all-controls">
        <div id="batch-progress" class="print-all-progress">
            <?php if ($requestError !== '') { ?>
                <div class="alert alert-danger"><?= htmlspecialchars($requestError, ENT_QUOTES, CHARSET) ?></div>
            <?php } elseif ($orderIds === []) { ?>
                <div class="alert alert-info"><?= TEXT_NO_ORDERS ?></div>
            <?php } else { ?>
                <div class="alert alert-info" id="batch-status"></div>
                <div class="progress"><div id="batch-progress-bar" class="progress-bar" role="progressbar" style="width:0%"></div></div>
            <?php } ?>
        </div>
        <div class="print-all-actions text-center">
            <button type="button" id="print-batch" class="btn btn-primary" hidden onclick="window.print()"><?= TEXT_PRINT ?></button>
            <button type="button" class="btn btn-default" onclick="window.close()"><?= TEXT_CLOSE ?></button>
        </div>
    </div>
    <main id="batch-documents"></main>
<?php } ?>

<?php if ($generate && $requestError === '' && $orderIds !== []) { ?>
<script>
(() => {
    'use strict';
    const orderIds = <?= json_encode($orderIds, JSON_THROW_ON_ERROR) ?>;
    const endpoint = <?= json_encode($documentTypes[$selectedType]['endpoint'], JSON_THROW_ON_ERROR) ?>;
    const documentName = <?= json_encode(count($orderIds) === 1 ? $documentTypes[$selectedType]['singular'] : $documentTypes[$selectedType]['plural'], JSON_THROW_ON_ERROR) ?>;
    const loadingTemplate = <?= json_encode(TEXT_LOADING, JSON_THROW_ON_ERROR) ?>;
    const readyTemplate = <?= json_encode(TEXT_READY, JSON_THROW_ON_ERROR) ?>;
    const errorTemplate = <?= json_encode(TEXT_LOAD_ERROR, JSON_THROW_ON_ERROR) ?>;
    const sessionExpired = <?= json_encode(TEXT_SESSION_EXPIRED, JSON_THROW_ON_ERROR) ?>;
    const container = document.getElementById('batch-documents');
    const status = document.getElementById('batch-status');
    const progress = document.getElementById('batch-progress-bar');

    const format = (template, values) => template.replace(/%(\d+)\$[ds]/g, (_, index) => String(values[Number(index) - 1]));

    async function loadDocument(orderId) {
        const response = await fetch(`${endpoint}?oID=${encodeURIComponent(orderId)}`, {
            credentials: 'same-origin',
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        });
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        if (response.redirected && /(?:login|password_forgotten)\.php/i.test(response.url)) {
            throw new Error(sessionExpired);
        }

        const source = new DOMParser().parseFromString(await response.text(), 'text/html');
        const sourceContent = source.querySelector('.container') || source.body;
        if (!sourceContent || !sourceContent.textContent.trim()) {
            throw new Error('Empty response');
        }
        sourceContent.querySelectorAll('script').forEach((script) => script.remove());

        const section = document.createElement('section');
        section.className = 'batch-document';
        section.dataset.orderId = String(orderId);
        section.innerHTML = sourceContent.innerHTML;
        container.appendChild(section);
    }

    async function prepareBatch() {
        status.textContent = format(loadingTemplate, [orderIds.length, documentName]);
        let loaded = 0;

        for (const orderId of orderIds) {
            try {
                await loadDocument(orderId);
            } catch (error) {
                const message = document.createElement('div');
                message.className = 'alert alert-danger batch-error';
                message.textContent = format(errorTemplate, [orderId, error.message]);
                container.appendChild(message);
            }
            loaded++;
            const percent = Math.round((loaded / orderIds.length) * 100);
            progress.style.width = `${percent}%`;
            progress.textContent = `${percent}%`;
        }

        const images = Array.from(container.images);
        await Promise.all(images.map((img) => img.complete ? Promise.resolve() : new Promise((resolve) => {
            img.addEventListener('load', resolve, {once: true});
            img.addEventListener('error', resolve, {once: true});
        })));

        status.className = 'alert alert-success';
        status.textContent = format(readyTemplate, [loaded, documentName]);
        document.getElementById('print-batch').hidden = false;
    }

    prepareBatch();
})();
</script>
<?php } ?>
</body>
</html>
<?php require DIR_WS_INCLUDES . 'application_bottom.php';
