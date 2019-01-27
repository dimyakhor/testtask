<?php

class CurrencyChecker
{
    function getTodayCurrency()
    {
        return CbrCurrencyGetter::getCurrency(date('d/m/Y'));
    }

//    function getTodayCurrency()
//    {
//        return RbcCurrencyGetter::getCurrency(date('d/m/Y'));
//    }
}