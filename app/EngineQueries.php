<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class EngineQueries extends Model {

    protected $table = 'CONSULTAS_MOTOR_CM';

    protected $primaryKey = 'CM_CD_CONSULTA_MOTOR';

    public function getMyHistory()
    {
        $userQueries = EngineQueries::where('USUARIO', '=', Auth()->user()->id)->get();

        return $userQueries;
    }
}
