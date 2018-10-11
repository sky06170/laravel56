<?php

namespace App\Services;

use App\Repositories\CurrencyRecordRepository;
use App\Repositories\CurrencyCategoryRepository;

class ScheduleService
{
    protected $recordRepo, $categoryRepo;

    public function __construct(CurrencyRecordRepository $recordRepo, CurrencyCategoryRepository $categoryRepo)
    {
        $this->recordRepo = $recordRepo;

        $this->categoryRepo = $categoryRepo;
    }

    public function createCurrencyRecouds($lists)
    {
        $categorys = $this->categoryRepo->getLists();

        foreach ($categorys as $category) {
            foreach ($lists as $list) {
                if ($category->name == $list['name']) {
                    $data = [
                        'currency_category_id' => $category->id,
                        'bank' => $list['bank'],
                        'immediate_buy' => $list['immediate_buy'],
                        'immediate_sell' => $list['immediate_sell'],
                        'cash_buy' => $list['cash_buy'],
                        'cash_sell' => $list['cash_sell'],
                    ];
                    $this->recordRepo->createCurrencyRecouds($data);
                    break;
                }
            }
        }
    }
}