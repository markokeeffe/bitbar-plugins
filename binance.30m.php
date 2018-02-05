#!/usr/bin/env php
<?php
//error_reporting(E_ERROR | E_PARSE);
require ".bitbar/vendor/autoload.php";
use SteveEdson\BitBar;

$dotenv = new Dotenv\Dotenv(dirname(__FILE__));
$dotenv->load();

$bb = new BitBar();

try {
    $btcAudResponse = json_decode(file_get_contents('https://api.btcmarkets.net/market/BTC/AUD/tick'));
    $btcAudPrice = $btcAudResponse->lastPrice;

    $api = new Binance\API(getenv('BINANCE_API_KEY'), getenv('BINANCE_API_SECRET'));

    $ticker = $api->prices();
    $balances = $api->balances($ticker);
} catch (\Exception $e) {
    $bb->newLine()
        ->setText('Error')
        ->show();
    $bb->newLine()
        ->setText($e->getMessage())
        ->show(false);

    die();
}


$bb->newLine()
    ->setText(' $' . number_format($api->btc_value * $btcAudPrice, 2))
    ->setImage('iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAYagMeiWXwAAAAJiS0dEAACqjSMyAAAAB3RJTUUH4gEPFzs09jSHtwAAAQxJREFUKM+F0bEug2EYR/Hf+7UGbTpRi0Qskt4Ak4V892DCwEB1krSjwagSC2YaITG4gnezSNyAMJFYlEGkDJXWgKoY/MfnnOk5QW+Rgg1sekl712wfzlu3Kmipx9a3kunDVTV5A6YElwvtxo/whaty7jwbNvmjJEQyKmpyri1bdi2nqiITkUQSXQ/e3KiIooobb5q6kiirYNGVIy2Pzs0Ljq0oOjOrpJG1oezeklPBvB1BcKhr2r5R44l/lrXp1pULc5pOwIkZRWfKShohkmDRtidrIlJ7hlQdoJOkdAQjBk3YlUrtmjCoKOikwp9HMebVti2tlE/hl6If91o0LLRd6pr0rq6uFyv8l/sD05pbdsUidG8AAAAldEVYdGRhdGU6Y3JlYXRlADIwMTgtMDEtMTVUMjM6NTk6NTItMDU6MDAGOcxLAAAAJXRFWHRkYXRlOm1vZGlmeQAyMDE4LTAxLTE1VDIzOjU5OjUyLTA1OjAwd2R09wAAAABJRU5ErkJggg==')
    ->show();

$bb->newLine()
    ->setText('Holdings:')
    ->setUrl('')
    ->show(false);

$holdings = [];

foreach ($balances as $coin => $balance) {
    if ((float) $balance['available'] > 0 || (float) $balance['onOrder'] > 0) {

        if ((float) $balance['btcTotal'] < 0.00010000) {
            continue;
        }

        $total = (float) $balance['available'] + (float) $balance['onOrder'];

        $row = $coin . ': ' . number_format($total, 8) . ' (' . $balance['btcTotal'] . ' BTC) ($' . number_format($balance['btcTotal'] * $btcAudPrice, 2) . ')';

        $holdings[] = $row;
    }
}

$bb->newLine()
    ->setText(implode(PHP_EOL, $holdings))
    ->show(false);