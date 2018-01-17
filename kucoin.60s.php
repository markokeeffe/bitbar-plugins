#!/usr/bin/env php
<?php
require ".bitbar/vendor/autoload.php";
use SteveEdson\BitBar;

$dotenv = new Dotenv\Dotenv(dirname(__FILE__));
$dotenv->load();

define('HODLING', getenv('HOLDING_XRB'));

// Create BitBar formatter
$bb = new BitBar();


try {
    $btcAudResponse = json_decode(file_get_contents('https://api.btcmarkets.net/market/BTC/AUD/tick'));
    $btcAudPrice = $btcAudResponse->lastPrice;

    $xrpBtcResponse = json_decode(file_get_contents('https://api.kucoin.com/v1/open/tick?symbol=XRB-BTC'));
    $xrpBtcPrice = $xrpBtcResponse->data->lastDealPrice;
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
    ->setText('XRB: $' . number_format($xrpBtcPrice * $btcAudPrice, 2))
    ->show();

$bb->newLine()
    ->setText('Holdings: ' . number_format((float) HODLING, 2) . ' XRB ($' . number_format((HODLING * $xrpBtcPrice) * $btcAudPrice, 2) . ')')
    ->setUrl('https://www.kucoin.com/#/trade.pro/XRB-BTC')
    ->show(false);