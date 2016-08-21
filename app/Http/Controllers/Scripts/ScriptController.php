<?php

namespace App\Http\Controllers\Scripts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScriptController extends Controller
{

    public function __construct()
    {
    	$this->middleware('cors');
    }

    public function getSuburbs($town_id, Request $request)
    {
        $suburbs = app('soap')->getSuburbs($town_id);

        if (!$suburbs) {
            abort(404);
        }

        return response()->json($suburbs);
    }

    public function getTowns()
    {
        $towns = app('soap')->getTowns();

        if (!$towns) {
            abort(404);
        }

        return response()->json($towns);
    }

    public function getLocationTYpes()
    {
        $locationTypes = app('soap')->getLocationTypes();

        if (!$locationTypes) {
            abort(404);
        }

        return response()->json($locationTypes);
    }
}
