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
        try{
            $url = self::CBR_URL . '?date_req=' . date('d/m/Y', $timestamp);
            $xmlDataValues = simplexml_load_file($url);
            foreach ($xmlDataValues->Valute as $valute) {
                if ((int)$valute->NumCode === self::CBR_NUM_CODES[$currencyType]){
                    return [$currencyType => (float)str_replace(',','.',$valute->Value)];
                }
            }
            return [$currencyType => 0];
        } catch (Exception $e) {
            throw new Exception('Сервис получения курса валют из Центрального банка не доступен');
        }
    }

    private function generateRbcUrl($currencyType, $date) {
        return self::RBC_URL . '?currency_from=' . $currencyType . '&currency_to=RUR&source=cbrf&sum=1&date=' . $date;
    }

    private function getCurrencyFromRbc($currencyType, $timestamp)
    {
        try{
            $date = date('Y-m-d',$timestamp);
            $dataToParse = json_decode(file_get_contents($this->generateRbcUrl($currencyType, $date)));
            if (!$dataToParse->data) {
                return [$currencyType => 0];
            }
            $currencyVal = $dataToParse->data->sum_result;
            return [$currencyType => $currencyVal];
        } catch (Exception $e) {
            throw new Exception('Сервис получения курса валют из RBC не доступен');
        }
    }

    private function calculateAverageCurrency($currencyType, $timestamp) {
        $averageVal = ($this->getCurrencyFromCbr($currencyType, $timestamp)[$currencyType] +
            $this->getCurrencyFromRbc($currencyType, $timestamp)[$currencyType])/2;
        if (!$averageVal) {
            return null;
        }
        return [$currencyType => $averageVal];
    }

    function getTodayCurrencies()
    {
        return array_merge(
            $this->calculateAverageCurrency(self::USD, time()),
            $this->getCurrencyFromCbr(self::EUR, time())
        );
    }

    function getCurrencies($timestamp)
    {
        if (!is_int($timestamp) || $timestamp>time()) {
            return null;
        }

        $usdVal = $this->calculateAverageCurrency(self::USD, $timestamp);
        $eurVal = $this->getCurrencyFromCbr(self::EUR, $timestamp);

        if (!$usdVal || !$eurVal) {
            return null;
        }
        return array_merge($usdVal,$eurVal);
    }
}