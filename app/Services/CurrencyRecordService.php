<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CurrencyRecordService
{
    /**
     * 建立搜尋列的年份清單
     *
     * @return array
     */
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

    /**
     * 建立搜尋列的月份清單
     *
     * @return array
     */
    public function makeSearchBarMonths()
    {
        $months = [];
        for ($i=1; $i<=12; $i++) {
            array_push($months, $i);
        }
        return $months;
    }

    /**
     * 取得匯率差的利潤
     *
     * @param array $datas
     * @return int
     */
    public function getProfit($datas)
    {
        $investmentPrice = $datas['simulationInvestment'];
        $resultPrice = ($investmentPrice / $datas['simulationBuy']) * $datas['simulationSell'];
        return floor($resultPrice - $investmentPrice);
    }

    /**
     * 取得Highcharts匯率分析圖資訊
     *
     * @param [type] $datas
     * @return void
     */
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

        return [
            'highcharts_categories' => $highcharts_categories,
            'datas' => $this->getRecords($records),
        ];
    }

    /**
     * 取得所有匯率紀錄
     *
     * @param object $records
     * @return array
     */
    private function getRecords($records)
    {
        $immediateBuys = [];
        $immediateSells = [];
        $cashBuys = [];
        $cashSells = [];
        foreach ($records as $record) { 
            array_push($immediateBuys, $record->immediate_buy !== null ? (double) $record->immediate_buy : 0);
            array_push($immediateSells, $record->immediate_sell !== null ? (double) $record->immediate_sell : 0);
            array_push($cashBuys, $record->cash_buy !== null ? (double) $record->cash_buy : 0);
            array_push($cashSells, $record->cash_sell !== null ? (double) $record->cash_sell : 0);
        }

        return [
            $this->getReturnDatas('即時買進', $immediateBuys),
            $this->getReturnDatas('即時賣出', $immediateSells),
            $this->getReturnDatas('現金買進', $cashBuys),
            $this->getReturnDatas('現金賣出', $cashSells),
        ];
    }

    /**
     * 取得回傳的資料
     *
     * @param string $title
     * @param array $values
     * @return array
     */
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