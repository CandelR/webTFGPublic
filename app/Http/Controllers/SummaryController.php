<?php

namespace App\Http\Controllers;

use App\EngineQueries;
use App\EngineTypes;
use Illuminate\View\Engines\Engine;

class SummaryController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $engineQueries = new EngineQueries();
        $userQueries = $engineQueries->getMyHistory();

        $engineTypesClass = new EngineTypes();
        $engineTypes = $engineTypesClass->getEngineTypes();

        return view('summary', ["userQueries" => $userQueries, "engineTypes" => $engineTypes]);
    }

}
