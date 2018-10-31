<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CurrencyRecordController extends Controller
{
    public function getHighchartsInfo(Request $request)
    {
        try {
            if ($request->ajax()) {
                $service = service('CurrencyRecordService');
                $highchartsInfo = $service->getHighchartsInfo($request->all());

                return response()->json([
                    'status' => true,
                    'result' => $highchartsInfo
                ]);
            }  
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * 取得搜尋列的資訊
     *
     * @param Request $request
     * @return void
     */
    public function getSearchBarInfo(Request $request)
    {
        try {
            if ($request->ajax()) {
                $categoryRepo = repo('CurrencyCategoryRepository');
                $service = service('CurrencyRecordService');
                return response()->json([
                    'status' => true,
                    'categories' => $categoryRepo->getNames(),
                    'years' => $service->makeSearchBarYears(),
                    'months' => $service->makeSearchBarMonths()
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function getCaculateResult(Request $request)
    {
        try {
            if ($request->ajax()) {
                $datas = $request->all();
                $service = service('CurrencyRecordService');
                return response()->json([
                    'status' => true,
                    'profit' => $service->getProfit($datas)
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }
}
