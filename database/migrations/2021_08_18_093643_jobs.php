<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Jobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employer_id');
            $table->bigInteger('category_id')->nullable()->comment('job field');
            $table->bigInteger('job_specialize')->nullable()->comment('job_specialize');
            $table->string('title');
            $table->text('details');
            $table->bigInteger('country_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();
            $table->text('note')->nullable();
            $table->float('salary')->nullable();
            $table->tinyInteger('gender')->nullable()->comment('0->male  1->female 2->other');
            $table->integer('experience')->nullable();
            $table->string('qualification')->nullable();
            $table->string('interviewer_name')->nullable();
            $table->text('interviewer_role')->nullable();
            $table->date('meeting_date')->nullable();
            $table->time('meeting_from')->nullable();
            $table->time('meeting_to')->nullable();
            $table->integer('meeting_time')->nullable();
            $table->tinyInteger('status')->nullable()->comment('0->cancel 1->active 2->closed');
            $table->integer('applies')->nullable();
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
        Schema::dropIfExists('jobs');
    }
}
