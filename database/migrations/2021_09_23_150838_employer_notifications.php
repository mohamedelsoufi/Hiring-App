<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployerNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employerNotifications', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->comment('1->employee aplay job');
            $table->bigInteger('employer_id');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->bigInteger('candate_id')->nullable();
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
        Schema::dropIfExists('employerNotifications');
    }
}
