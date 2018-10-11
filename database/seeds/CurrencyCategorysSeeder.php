<?php

use Illuminate\Database\Seeder;
use App\Models\CurrencyCategory;

class CurrencyCategorysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lists = [
            [
                'name' => '美元',
                'alias' => '美圓,美幣',
            ],
            [
                'name' => '澳幣',
                'alias' => '澳圓,澳元',
            ],
            [
                'name' => '加拿大幣',
                'alias' => '加拿大圓,加拿大元',
            ],
            [
                'name' => '港幣',
                'alias' => '港圓,港元',
            ],
            [
                'name' => '英鎊',
                'alias' => '英圓,英元,英幣,英國幣',
            ],
            [
                'name' => '瑞士法郎',
                'alias' => '瑞士圓,瑞士元,瑞士幣',
            ],
            [
                'name' => '日圓',
                'alias' => '日元,日幣',
            ],
            [
                'name' => '歐元',
                'alias' => '歐圓,歐幣',
            ],
            [
                'name' => '紐西蘭幣',
                'alias' => '紐西蘭圓,紐西蘭元',
            ],
            [
                'name' => '新加坡幣',
                'alias' => '新加坡圓,新加坡元',
            ],
            [
                'name' => '南非幣',
                'alias' => '南非圓,南非元',
            ],
            [
                'name' => '瑞典克朗',
                'alias' => '瑞典圓,瑞典元,瑞典幣',
            ],
            [
                'name' => '泰銖',
                'alias' => '泰圓,泰元,泰幣,泰國幣',
            ],
            [
                'name' => '人民幣',
                'alias' => '',
            ],
            [
                'name' => '印度幣',
                'alias' => '印度圓,印度元,印幣',
            ],
            [
                'name' => '丹麥幣',
                'alias' => '丹麥圓,丹麥元',
            ],
            [
                'name' => '土耳其里拉',
                'alias' => '土耳其圓,土耳其元,土耳其幣',
            ],
            [
                'name' => '墨西哥披索',
                'alias' => '墨西哥圓,墨西哥元,墨西哥幣',
            ],
            [
                'name' => '越南幣',
                'alias' => '越南圓,越南元,越幣',
            ],
            [
                'name' => '菲律賓披索',
                'alias' => '菲律賓圓,菲律賓元,菲律賓幣,菲幣',
            ],
            [
                'name' => '馬來西亞幣',
                'alias' => '馬來西亞圓,馬來西亞元,馬幣,馬元',
            ],
            [
                'name' => '韓圜',
                'alias' => '韓圓,韓幣,韓元',
            ],
            [
                'name' => '印尼盾',
                'alias' => '印尼圓,印尼元,印尼幣',
            ],
        ];

        $this->setDefaultCategorys($lists);
    }

    private function setDefaultCategorys($lists)
    {
        foreach ($lists as $item) {
            CurrencyCategory::create(
                [
                    'name' => $item['name'],
                    'alias' => $item['alias'],
                ]
            );
        }
    }
}
