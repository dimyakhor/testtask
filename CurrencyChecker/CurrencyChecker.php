<?php

class CurrencyChecker
{

    const CBR_URL = 'http://www.cbr.ru/scripts/XML_daily.asp';
    const RBC_URL = 'https://cash.rbc.ru/cash/json/converter_currency_rate/';

    const USD = 'USD';
    const EUR = 'EUR';

    const CBR_NUM_CODES = [
        'USD' => 840,
        'EUR' => 978
    ];

    private function getCurrencyFromCbr($currencyType, $timestamp)
    {
        $url = self::CBR_URL . '?date_req=' . date('d/m/Y', $timestamp);
        $xmlDataValues = simplexml_load_file($url);
        foreach ($xmlDataValues->Valute as $valute) {
            if ((int)$valute->NumCode === self::CBR_NUM_CODES[$currencyType]){
                return [$currencyType => (float)str_replace(',','.',$valute->Value)];
            }
        }
        return null;
    }

    private function generateRbcUrl($currencyType, $date) {
        return self::RBC_URL . '?currency_from=' . $currencyType . '&currency_to=RUR&source=cbrf&sum=1&date=' . $date;
    }

    private function getCurrencyFromRbc($currencyType, $timestamp)
    {
        $date = date('Y-m-d',$timestamp);
        return [
            $currencyType => json_decode(file_get_contents($this->generateRbcUrl($currencyType, $date)))->data->sum_result
        ];
    }

    private function calculateAverageCurrency($currencyType, $timestamp) {
        return [
            $currencyType => ($this->getCurrencyFromCbr($currencyType, $timestamp)[$currencyType] +
                              $this->getCurrencyFromRbc($currencyType, $timestamp)[$currencyType])/2
        ];
    }

    function getTodayCurrency()
    {
        var_dump($this->calculateAverageCurrency(self::USD, time()));
        var_dump($this->getCurrencyFromCbr(self::EUR, time()));
    }
}