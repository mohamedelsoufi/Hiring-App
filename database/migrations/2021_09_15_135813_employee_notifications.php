<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployeeNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employeeNotifications', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->comment('1->accept candate befor interview, 2->video call, 3->accept or reject after interview, 4 -> create job');
            $table->bigInteger('employee_id');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->bigInteger('job_id')->nullable();
            $table->bigInteger('candate_id')->nullable();
            $table->bigInteger('employer_id')->nullable();
            $table->text('viedo_channel_name')->nullable();
            $table->text('viedo_token')->nullable();
            $table->date('read_at')->nullable();
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
        Schema::dropIfExists('employeeNotifications');
    }
}
