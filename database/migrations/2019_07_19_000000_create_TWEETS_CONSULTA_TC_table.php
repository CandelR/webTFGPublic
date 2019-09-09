<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTweetsConsultaTCTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TWEETS_CONSULTA_TC', function (Blueprint $table) {
            $table->bigInteger('CM_CD_CONSULTA_MOTOR');
            $table->integer('TC_NUMERO_TWEET');
            $table->string('TC_TEXTO_TWEET');
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
        Schema::dropIfExists('TWEETS_CONSULTAS_TC');
    }
}
