<?php

require_once 'CurrencyChecker.php';

$dateTestCases = [
    null => null,
    '2019-01-26' => null,
    '26-01-2019' => null,
    0 => null,
    8 => null,
    'dsfds' => null,
    '20190126' => null,
    '2019/01/26' => null,
    '26/01/2019' => null,
    time() => ['USD' => 65.917,'EUR' => 74.6312],
    time()+100 => null
];

$currencyChecker = new CurrencyChecker();

foreach ($dateTestCases as $testValue => $rightResult) {
    $result = $currencyChecker->getCurrencies($testValue);
    if ($result === $rightResult) {
        echo 'Тесткейс с проверкой значения ' . $testValue . " успешно пройден\n";
    } else {
        echo 'Тесткейс с проверкой значения ' . $testValue . " ПРОВАЛЕН!!!\n";
    }
}
