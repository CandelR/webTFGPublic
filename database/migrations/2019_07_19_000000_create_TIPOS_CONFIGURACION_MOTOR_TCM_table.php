<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiposConfiguracionMotorTCMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TIPOS_CONFIGURACION_MOTOR_TCM', function (Blueprint $table) {
            $table->integer('TCM_CD_MOTOR');
            $table->string('TCM_VALOR');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TIPOS_CONFIGURACION_MOTOR_TCM');
    }
}
