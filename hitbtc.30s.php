#!/usr/bin/env php
<?php
require ".bitbar/vendor/autoload.php";
use SteveEdson\BitBar;

define('HODLING', getenv('HOLDING_BCH'));

// Create BitBar formatter
$bb = new BitBar();


try {
    $btcAudResponse = json_decode(file_get_contents('https://api.btcmarkets.net/market/BTC/AUD/tick'));
    $btcAudPrice = $btcAudResponse->lastPrice;

    $response = json_decode(file_get_contents('https://api.hitbtc.com/api/2/public/ticker/BCHBTC'));
    $exchangeBtcPrice = $response->last;
} catch (\Exception $e) {
    $bb->newLine()
        ->setText('BCH: Error')
        ->show();

    $bb->divide();

    $bb->newLine()
        ->setText($e->getMessage())
        ->show();

    die();
}


$bb->newLine()
    ->setText('BCH: ' . number_format($exchangeBtcPrice, 4))
    ->show();

$bb->divide();

$bb->newLine()
    ->setText('Holdings: $' . number_format((HODLING * $exchangeBtcPrice) * $btcAudPrice, 2))
    ->setUrl('https://hitbtc.com/exchange/BCH-to-BTC')
    ->show();