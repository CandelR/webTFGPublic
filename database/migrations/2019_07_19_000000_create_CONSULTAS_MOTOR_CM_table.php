<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultasMotorCMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CONSULTAS_MOTOR_CM', function (Blueprint $table) {
            $table->bigIncrements('CM_CD_CONSULTA_MOTOR');
            $table->integer('TCM_CD_MOTOR');
            $table->string('CM_PALABRA_BUSQUEDA');
            $table->integer('CM_NUMERO_TWEETS');
            $table->bigInteger('USUARIO');
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
        Schema::dropIfExists('CONSULTAS_MOTOR_CM');
    }
}
