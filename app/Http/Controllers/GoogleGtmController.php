<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoogleGtmController extends Controller
{
    public function index()
    {
    	return response()->view('googlegtm');
    }
}
