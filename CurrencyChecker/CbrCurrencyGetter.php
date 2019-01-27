<?php

class CbrCurrencyGetter
{
    const CBR_URL = 'http://www.cbr.ru/scripts/XML_daily.asp';
    const CBR_URL_GET_PARAM = 'date_req';
    const USD = 'usd';
    const EUR = 'eur';
    const NUM_CODES = [
        'usd' => 840,
        'eur' => 978
    ];

    static public function getCurrency($date)
    {
        $url = self::CBR_URL . '?' . self::CBR_URL_GET_PARAM . '=' . $date;
        $xmlDataValues = simplexml_load_file($url);
        $valuteValues = [];
        foreach ($xmlDataValues->Valute as $valute) {
            if ((int)$valute->NumCode === self::NUM_CODES[self::USD]){
                $valuteValues[self::USD] = (string)$valute->Value;
            }
            if ((int)$valute->NumCode === self::NUM_CODES[self::EUR]){
                $valuteValues[self::EUR] = (string)$valute->Value;
            }
        }
        return $valuteValues;
    }
}