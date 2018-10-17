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

                $maxDay = Carbon::now()->endOfMonth()->day;
                $categories = [];
                $records = [];
                for ($i = 1; $i <= $maxDay; $i++) {
                    $datatime = $year.'-'.$month.'-'.$i.' 19:0';
                    $record = $recordRepo->getHighchartsRecords($currencyCategory, $datatime);
                    if ($record !== null) {
                        array_push($categories, $i);
                        array_push($records, $record);
                    }
                }

                $immediateBuys = $this->getImmediateBuy($records);
                $immediateSells = $this->getImmediateSell($records);
                $cashBuys = $this->getCashBuy($records);
                $cashSells = $this->getCashSell($records);

                return response()->json([
                    'status' => true,
                    'categories' => $categories,
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

        return response()->json([
            'categories' => $categoryRepo->getNames()
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
