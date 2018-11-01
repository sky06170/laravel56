<?php

namespace App\Services;

use Carbon\Carbon;

class CurrencyRecordService
{
    public function makeSearchBarYears()
    {
        $nowYear = Carbon::now()->year;
        $startYear = config('currency.highcharts_start_year');
        $years = [$startYear];
        if ($nowYear > $startYear) {
            for ($i = $startYear; $i<$nowYear; $i++) {
                array_push($years, $i);
            }
        }
        return $years;
    }

    public function makeSearchBarMonths()
    {
        $months = [];
        for ($i=1; $i<=12; $i++) {
            array_push($months, $i);
        }
        return $months;
    }

    public function getProfit($datas)
    {
        $investmentPrice = $datas['simulationInvestment'];
        $resultPrice = ($investmentPrice / $datas['simulationBuy']) * $datas['simulationSell'];
        return floor($resultPrice - $investmentPrice);
    }

    public function getHighchartsInfo($datas)
    {
        $recordRepo = repo('CurrencyRecordRepository');

        if ($datas['view_mode'] == '日') {
            $maxDay = Carbon::createFromFormat('Y-m-d', $datas['year'].'-'.$datas['month'].'-01', 'Asia/Taipei')->endOfMonth()->day;
            $highcharts_categories = [];
            $records = [];
            for ($i = 1; $i <= $maxDay; $i++) {
                $datatime = $datas['year'].'-'.$datas['month'].'-'.$i.' 00:0';
                $record = $recordRepo->getHighchartsRecords($datas['category'], $datatime);
                if ($record !== null) {
                    array_push($highcharts_categories, $i);
                    array_push($records, $record);
                }
            }
        } else {
            $maxHour = 23;
            $highcharts_categories = [];
            $records = [];
            for ($i = 0; $i <= $maxHour; $i++) {
                $hour = ($i < 10) ? '0'.$i : $i;
                $datatime = $datas['year'].'-'.$datas['month'].'-'.$datas['day'].' '.$hour;
                $record = $recordRepo->getHighchartsRecords($datas['category'], $datatime);
                if ($record !== null) {
                    array_push($highcharts_categories, $i);
                    array_push($records, $record);
                }
            }
        }

        $datas = [
            $this->getImmediateBuy($records),
            $this->getImmediateSell($records),
            $this->getCashBuy($records),
            $this->getCashSell($records)
        ];

        return [
            'highcharts_categories' => $highcharts_categories,
            'datas' => $datas,
        ];
    }

    //銀行即時買進
    private function getImmediateBuy($records)
    {
        $values = [];
        foreach ($records as $record) {
            $value = 0;
            if ($record->immediate_buy !== null) {
                $value = (double) $record->immediate_buy;
            }
            array_push($values, $value);
        }
        return $this->getReturnDatas('即時買進', $values);
    }

    //銀行即時賣出
    private function getImmediateSell($records)
    {
        $values = [];
        foreach ($records as $record) {
            $value = 0;
            if ($record->immediate_sell !== null) {
                $value = (double) $record->immediate_sell;
            }
            array_push($values, $value);
        }
        return $this->getReturnDatas('即時賣出', $values);
    }

    //銀行現金買進
    private function getCashBuy($records)
    {
        $values = [];
        foreach ($records as $record) {
            $value = 0;
            if ($record->cash_buy !== null) {
                $value = (double) $record->cash_buy;
            }
            array_push($values, $value);
        }
        return $this->getReturnDatas('現金買進', $values);
    }

    //銀行現金賣出
    private function getCashSell($records)
    {
        $values = [];
        foreach ($records as $record) {
            $value = 0;
            if ($record->cash_sell !== null) {
                $value = (double) $record->cash_sell;
            }
            array_push($values, $value);
        }
        return $this->getReturnDatas('現金賣出', $values);
    }

    private function getReturnDatas($title, $values)
    {
        return [
            'title' => $title,
            'values' => $values,
            'maxValue' => collect($values)->max(),
            'minValue' => collect($values)->min()
        ];
    }
}