<?php

$dictionary = array(
    'OKEY_GOOGLE_DIALOGFLOW_CLIENT_TOKEN' => 'Токен клиета',
    'OKEY_GOOGLE_DIALOGFLOW_DEVELOPER_TOKEN' => 'Токен разработчика',
);

foreach ($dictionary as $k => $v) {
    if (!defined('LANG_' . $k)) {
        define('LANG_' . $k, $v);
    }
}