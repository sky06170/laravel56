<?php

namespace App\Repositories;

use App\Models\CurrencyRecord;
use App\Models\CurrencyCategory;

class CurrencyRecordRepository
{
    protected $model;

    public function __construct(CurrencyRecord $model)
    {
        $this->model = $model;
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