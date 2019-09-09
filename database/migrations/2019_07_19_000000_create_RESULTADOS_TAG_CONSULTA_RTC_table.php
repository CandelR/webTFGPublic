<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultadosTagConsultaRTCTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('RESULTADOS_TAG_CONSULTA_RTC', function (Blueprint $table) {
            $table->bigInteger('CM_CD_CONSULTA_MOTOR');
            $table->integer('TC_NUM_TWEET');
            $table->float('RTC_CONFIANZA');
            $table->string('RTC_PREDICION');
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
        Schema::dropIfExists('RESULTADOS_TAG_CONSULTA_RTC');
    }
}
