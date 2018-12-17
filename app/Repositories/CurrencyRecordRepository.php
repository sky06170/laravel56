<?php

namespace App\Repositories;

use App\Models\CurrencyRecord;
use App\Models\CurrencyCategory;
use Illuminate\Support\Facades\Log;

class CurrencyRecordRepository
{
    protected $model;

    public function __construct(CurrencyRecord $model)
    {
        $this->model = $model;
    }

    public function getHighchartsRecords($currencyCategory, $datatime)
    {
        $category = CurrencyCategory::where('name', $currencyCategory)->first();
        if ($category !== null) {
            return $this->model->where('currency_category_id', $category->id)
                ->where('created_at', 'like', '%'.$datatime.'%')
                ->first();
        }
        return null;
    }

    public function createCurrencyRecouds($data)
    {
        $data = $this->clearNullData($data);
        return $this->model->create($data);
    }

    private function clearNullData($data)
    {
        foreach ($data as $key => $item) {
            if ($data[$key] == '-') {
                $data[$key] = null;
            }
        }
        return $data;
    }
}