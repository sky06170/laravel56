<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CurrencyRecordController extends Controller
{
    public function getHighcharts(Request $request)
    {
        if ($request->ajax()) {
            try {
                $recordRepo = repo('CurrencyRecordRepository');

                $currencyCategory = $request->input('category');
                $year = $request->input('year');
                $month = $request->input('month');

                if ($request->input('view_mode') == '日') {
                    $maxDay = Carbon::now()->endOfMonth()->day;
                    $highcharts_categories = [];
                    $records = [];
                    for ($i = 1; $i <= $maxDay; $i++) {
                        $datatime = $year.'-'.$month.'-'.$i.' 00:0';
                        $record = $recordRepo->getHighchartsRecords($currencyCategory, $datatime);
                        if ($record !== null) {
                            array_push($highcharts_categories, $i);
                            array_push($records, $record);
                        }
                    }
                } else {
                    $day = $request->input('day');
                    $maxHour = 23;
                    $highcharts_categories = [];
                    $records = [];
                    for ($i = 0; $i <= $maxHour; $i++) {
                        $hour = ($i < 10) ? '0'.$i : $i;
                        $datatime = $year.'-'.$month.'-'.$day.' '.$hour;
                        $record = $recordRepo->getHighchartsRecords($currencyCategory, $datatime);
                        if ($record !== null) {
                            array_push($highcharts_categories, $i);
                            array_push($records, $record);
                        }
                    }
                }

                $immediateBuys = $this->getImmediateBuy($records);
                $immediateSells = $this->getImmediateSell($records);
                $cashBuys = $this->getCashBuy($records);
                $cashSells = $this->getCashSell($records);

                return response()->json([
                    'status' => true,
                    'categories' => $highcharts_categories,
                    'records' => $records,
                    'immediateBuys' => $immediateBuys,
                    'immediateSells' => $immediateSells,
                    'cashBuys' => $cashBuys,
                    'cashSells' => $cashSells
                ]);
            } catch (\Exception $e) {
                return response()->json(['status' => false, 'msg' => $e->getMessage()]);
            }
        }
    }

    public function getSearchBarInfo()
    {
        $categoryRepo = repo('CurrencyCategoryRepository');

        $nowYear = Carbon::now()->year;
        $startYear = config('currency.highcharts_start_year');
        $years = [$startYear];
        if ($nowYear > $startYear) {
            for ($i = $startYear; $i<$nowYear; $i++) {
                array_push($years, $i);
            }
        }

        $months = [];
        for ($i=1; $i<=12; $i++) {
            array_push($months, $i);
        }

        return response()->json([
            'categories' => $categoryRepo->getNames(),
            'years' => $years,
            'months' => $months
        ]);
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
        return [
            'title' => '即時買進',
            'values' => $values
        ];
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
        return [
            'title' => '即時賣出',
            'values' => $values
        ];
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
        return [
            'title' => '現金買進',
            'values' => $values
        ];
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
        return [
            'title' => '現金賣出',
            'values' => $values
        ];
    }
}
