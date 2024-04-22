<?php

function getIP() {
    return @$_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
}

$str = getIP() . '-' . @file_get_contents('php://input') . '-' . time() . "\n";

$handle = fopen(__DIR__ . '/out.txt', 'a');
fwrite($handle, $str);
fclose($handle);

?>
{"status": "ok"}