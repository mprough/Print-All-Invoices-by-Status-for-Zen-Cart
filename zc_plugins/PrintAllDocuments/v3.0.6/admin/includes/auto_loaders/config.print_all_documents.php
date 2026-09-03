<?php

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

$autoLoadConfig[200][] = [
    'autoType' => 'init_script',
    'loadFile' => 'init_print_all_documents.php',
];
