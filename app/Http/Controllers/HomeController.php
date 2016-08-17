<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop   = Input::get('shop');
        $server = config('shopify.domain');

        return view('app.install', [
            'title' => 'Install app',
            'shop'  => $shop,
        ]);
    }
}
