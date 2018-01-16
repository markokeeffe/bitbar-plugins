#!/usr/bin/env php
<?php
require ".bitbar/vendor/autoload.php";
use SteveEdson\BitBar;

define('HODLING', 1361);

// Create BitBar formatter
$bb = new BitBar();


try {
    // Get C20 status info from status endpoint
    $statusResponse = json_decode(file_get_contents('https://crypto20.com/status'));
    $nav = $statusResponse->nav_per_token;

    // Get USD-AUD exchange rate info from fixer
    $exchangeRateResponse = json_decode(file_get_contents('https://api.fixer.io/latest?base=USD&symbols=AUD'));
    $exchangeRate = $exchangeRateResponse->rates->AUD;
} catch (\Exception $e) {
    $bb->newLine()
        ->setText('Error')
        ->setImage('iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAYAAAFM0aXcAAAABGdBTUEAALGPC/xhBQAAAS1JREFUKBWdkj1KBEEQhbs38ScVVzZVRPAGHsBs8Rpm3kEEjc0UjA3NPMReQDBYMHc1ElkRHL/X86pZ1w7EgjdV9d6rme7pTonoum5feSmgh6ArNPldhYiBjbdqqNdLT50tpIGVbfIEbJW5UCFGMSLjNIQgb8Q6puS1ajA5rASFuVEsS9oT5I4K8kSZ+OqTnwgZtDbeOxDHPybUQO6COYg4Lia6UzNXNh65f9XUOZgVpx/0J+IWV72ol1riJ9jAWV9Lfwn6QYT2gnDUwPR7K1V1gekA3APFCzgDm8u+0ksA2vIMRDxTaKd3QTjrDoxBXfOFhTfyYesL8Kvg2j6tph5wfyNT+mDwoTWcc57DP1or/vjPcUdXEJuHry+j7Xk4/OXs/79nv60mvvKnv/0NlHh3XudhrrUAAAAASUVORK5CYII=')
        ->show();

    $bb->divide();

    $bb->newLine()
        ->setText($e->getMessage())
        ->show();

    die();
}

// Show C20 icon and NAV
$bb->newLine()
    ->setText($nav)
    ->setImage('iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAYAAAFM0aXcAAAABGdBTUEAALGPC/xhBQAAAS1JREFUKBWdkj1KBEEQhbs38ScVVzZVRPAGHsBs8Rpm3kEEjc0UjA3NPMReQDBYMHc1ElkRHL/X86pZ1w7EgjdV9d6rme7pTonoum5feSmgh6ArNPldhYiBjbdqqNdLT50tpIGVbfIEbJW5UCFGMSLjNIQgb8Q6puS1ajA5rASFuVEsS9oT5I4K8kSZ+OqTnwgZtDbeOxDHPybUQO6COYg4Lia6UzNXNh65f9XUOZgVpx/0J+IWV72ol1riJ9jAWV9Lfwn6QYT2gnDUwPR7K1V1gekA3APFCzgDm8u+0ksA2vIMRDxTaKd3QTjrDoxBXfOFhTfyYesL8Kvg2j6tph5wfyNT+mDwoTWcc57DP1or/vjPcUdXEJuHry+j7Xk4/OXs/79nv60mvvKnv/0NlHh3XudhrrUAAAAASUVORK5CYII=')
    ->show();

$bb->divide();

// Calculate holdings in AUD
$bb->newLine()
    ->setText('Holdings: $' . number_format((HODLING * $nav) * $exchangeRate, 2))
    ->show();
$bb->divide();

// Show distribution of cryptocurrencies in the fund
$bb->newLine()
    ->setText('Fund Holdings: ')
    ->setUrl('https://crypto20.com/en/portal/insights/')
    ->show();

$holdings = [];
foreach ($statusResponse->holdings as $holding) {
    $holdings[] = $holding->name . ': ' . number_format(($holding->value / $statusResponse->usd_value) * 100, 2) . '%';
}

$bb->newLine()
    ->setText(implode(PHP_EOL, $holdings))
    ->show();


// Link to C20 'dashboard'
$bb->newLine()
    ->setText('Dashboard')
    ->setUrl('https://cryptodash1.firebaseapp.com/')
    ->show();