<?php

namespace App\Repositories;

use App\Models\CurrencyCategory;

class CurrencyCategoryRepository
{

    protected $model;

    public function __construct(CurrencyCategory $model)
    {
        $this->model = $model;
    }

    public function getLists()
    {
        return $this->model->get();
    }

}