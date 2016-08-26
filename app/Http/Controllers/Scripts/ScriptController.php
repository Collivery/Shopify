<?php

namespace App\Http\Controllers\Scripts;

use App\Helper\Resolver;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mds\Collivery;

class ScriptController extends Controller
{
    /**
     * @var Collivery
     */
    private $collivery;
    /**
     * @var Resolver
     */
    private $resolver;

    public function __construct(Collivery $collivery, Resolver $resolver)
    {
        $this->middleware('cors');
        $this->collivery = $collivery;
        $this->resolver = $resolver;
    }

    public function getSuburbs($townName)
    {
        $townId = $this->resolver->getTownId($townName);
        $suburbs = $this->collivery->getSuburbs($townId);

        if (!$suburbs) {
            abort(404, 'Suburbs not found!');
        }
        $suburbs = array_values($suburbs);
        $result = array_combine($suburbs, $suburbs);

        return response()->json($result);
    }

    public function getTowns(Request $request)
    {
        $provinces = explode(',', $request->input('provinces'));

        if (!$provinces) {
            abort(404, 'Provinces not found');
        }

        $provincesMap = config('provinces');
        $result = array_combine($provinces, array_fill(0, count($provinces), []));

        foreach ($provinces as $province) {
            if (isset($provincesMap[$province])) {
                $provinceTowns = $this->collivery->getTowns('ZAF', $provincesMap[$province]);
                if (!empty($provinceTowns)) {
                    $provinceTowns = array_values($provinceTowns);
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
        $locationTypes = $this->collivery->getLocationTypes();

        if (!$locationTypes) {
            abort(404);
        }

        return response()->json($locationTypes);
    }
}
