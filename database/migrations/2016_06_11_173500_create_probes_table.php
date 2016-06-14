<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProbesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('probes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userId');
            $table->integer('timestamp');
            $table->string('macAddress', 18);
            $table->integer('signalStrength');
            $table->string('ssid');
            $table->string('manufacturerName');
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
        Schema::drop('probes');
    }
}
