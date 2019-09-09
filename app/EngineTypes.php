<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class EngineTypes extends Model
{

    protected $table = 'TIPOS_CONFIGURACION_MOTOR_TCM';

    protected $primaryKey = 'TCM_CD_MOTOR';


    public function getEngineTypes()
    {
        $engineTypes = EngineTypes::all();
        return $engineTypes;
    }

}
