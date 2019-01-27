<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once 'CurrencyChecker/RbcCurrencyGetter.php';
require_once 'CurrencyChecker/CbrCurrencyGetter.php';
require_once 'CurrencyChecker/CurrencyChecker.php';

$currencyChecker = new CurrencyChecker();

var_dump($currencyChecker->getTodayCurrency());
