<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployeeJob extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Employee_job', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('job_id');
            $table->bigInteger('employee_id');
            $table->tinyInteger('candat_applay_status')->nullable()->comment('0->reject 1->accept 2->shoertlist');
            $table->bigInteger('avmeeting_id')->nullable();
            $table->tinyInteger('meeting_time_status')->nullable()->comment('0->reject 1->accept the candit who determine this');
            $table->text('note')->nullable();
            $table->tinyInteger('candat_status')->nullable()->comment('0->reject 1->accept 2->underreview employer who detemine this');;
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
        Schema::dropIfExists('Employee_job');
    }
}
