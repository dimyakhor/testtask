<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once 'CurrencyChecker/CurrencyChecker.php';

$currencyChecker = new CurrencyChecker();

var_dump($currencyChecker->getTodayCurrencies());

var_dump($currencyChecker->getCurrencies(strtotime('20-01-2019')));