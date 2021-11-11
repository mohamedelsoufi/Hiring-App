<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvmeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avmeetings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('job_id');
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
            $table->tinyInteger('available')->nullable()->comment('0->available 1->book');
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
        Schema::dropIfExists('avmeetings');
    }
}
