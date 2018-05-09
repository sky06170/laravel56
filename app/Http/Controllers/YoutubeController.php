<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YoutubeController extends Controller
{
    public function index()
    {
    	return response()->view('embed.youtube');
    }
}
