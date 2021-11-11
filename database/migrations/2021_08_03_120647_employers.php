<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Employers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employers', function (Blueprint $table) {
            $table->id();

            $table->string('fullName', 250);
            $table->string('title', 250);
            $table->string('email', 250);
            $table->string('password');
            $table->string('mobile_number1', 250)->nullable();
            $table->string('mobile_number2', 250)->nullable();
            $table->string('company_name', 250)->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('business')->nullable();
            $table->string('established_at')->nullable();
            $table->text('website', 250)->nullable();
            $table->string('image', 250)->nullable();
            $table->tinyInteger('active')->nullable();
            $table->text('token')->nullable();
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
        Schema::dropIfExists('employers');
    }
}
