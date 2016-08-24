<?php

namespace App\Http\Controllers\Scripts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScriptController extends Controller
{

    public function __construct(\Mds\Collivery $collivery)
    {

        $this->middleware('cors');
    }

    public function getSuburbs($townName, Request $request)
    {
        $towns   = app('soap')->getTowns();
        $townId  = app('resolver')->getTownId($townName);
        $suburbs = app('soap')->getSuburbs($townId);

        if (!$suburbs) {
            abort(404, 'Suburbs not found!');
        }
        $suburbs = array_values($suburbs);
        $result  = array_combine($suburbs, $suburbs);
        return response()->json($result);
    }

    public function getTowns(Request $request)
    {

        $provinces = explode(',', $request->input('provinces'));

        if (!$provinces) {
            abort(404, 'Provinces not found');
        }

        $provincesMap = config('provinces');
        $result       = array_combine($provinces, array_fill(0, count($provinces), []));

        foreach ($provinces as $province) {
            if (isset($provincesMap[$province])) {
                $provinceTowns = app('soap')->getTowns('ZAF', $provincesMap[$province]);
                if (!empty($provinceTowns)) {
                    $provinceTowns     = array_values($provinceTowns);
                    $result[$province] = array_combine($provinceTowns, $provinceTowns);
                }
            }
        }

        if (!$result) {
            abort(404, 'Bad request');
        }

        return response()->json($result);
    }

    public function getLocationTypes()
    {
        $locationTypes = app('soap')->getLocationTypes();

        if (!$locationTypes) {
            abort(404);
        }

        return response()->json($locationTypes);
    }
}
