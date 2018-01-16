#!/usr/bin/env php
<?php
require ".bitbar/vendor/autoload.php";
use SteveEdson\BitBar;

$dotenv = new Dotenv\Dotenv(dirname(__FILE__));
$dotenv->load();

define('HODLING', getenv('HOLDING_XRP'));

// Create BitBar formatter
$bb = new BitBar();


try {
    $xrpAudResponse = json_decode(file_get_contents('https://api.btcmarkets.net/market/XRP/AUD/tick'));
    $xrpAudPrice = $xrpAudResponse->lastPrice;
} catch (\Exception $e) {
    $bb->newLine()
        ->setText('BCH: Error')
        ->show();

    $bb->newLine()
        ->setText($e->getMessage())
        ->show(false);

    die();
}

$bb->newLine()
    ->setText('XRP: $' . number_format($xrpAudPrice, 2))
    ->show();

$bb->newLine()
    ->setText('Holdings: ' . number_format((float) HODLING, 2) . ' XRP ($' . number_format(HODLING * $xrpAudPrice, 2) . ')')
    ->setUrl('https://www.btcmarkets.net/trading/buysell')
    ->show(false);