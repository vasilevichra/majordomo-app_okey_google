<?php

$dictionary = array(
    'OKEY_GOOGLE_DIALOGFLOW_CLIENT_TOKEN' => 'Client token',
    'OKEY_GOOGLE_DIALOGFLOW_DEVELOPER_TOKEN' => 'Developer token',
);

foreach ($dictionary as $k => $v) {
    if (!defined('LANG_' . $k)) {
        define('LANG_' . $k, $v);
    }
}