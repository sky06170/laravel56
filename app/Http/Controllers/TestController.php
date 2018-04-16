<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class TestController extends Controller
{

    public function test()
    {
        $s_time = microtime(true);
        $method = 'get';
        $uri = 'https://tw.money.yahoo.com/currency-converter';
        $formParams = [];
        $response = $this->sendRequest($method,$uri,$formParams);
        $body = $response->getBody();
        $contents = $body->getContents();
        $datas = $this->analyticHtml($contents);
        $e_time = microtime(true);
        $r_time = $e_time - $s_time;
    }

    private function analyticHtml($contents)
    {
        $crawler = new Crawler($contents);

        $datas = [];

        $crawler->filterXPath('//table[@class="W-100 simple"]/tbody/tr')->each(function(Crawler $node, $i) use(&$datas) {
            $datas[$i]['name'] = $node->filterXPath('//td[1]/div')->text();
            $datas[$i]['bank'] = $node->filterXPath('//td[2]/div/a')->text();
            $datas[$i]['immediate_buy'] = $node->filterXPath('//td[3]')->text();
            $datas[$i]['immediate_sell'] = $node->filterXPath('//td[4]')->text();
            $datas[$i]['cash_buy'] = $node->filterXPath('//td[5]')->text();
            $datas[$i]['cash_sell'] = $node->filterXPath('//td[6]')->text();
        });

        return $datas;
    }

    private function sendRequest($method,$uri,$formParams)
    {
        $client = new Client();
        return $client->request($method,$uri,[
            'form_params' => $formParams,
            // 'headers' => [
            //     'Content-Type' => 'application/json'
            // ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
