<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Employees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->nullable();
            $table->string('fullName', 250);
            $table->string('email', 250);
            $table->string('password');
            $table->bigInteger('country_id')->nullable();
            $table->string('phone')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->string('title', 250)->nullable();
            $table->string('qualification', 250)->nullable();
            $table->string('university', 250)->nullable();
            $table->integer('graduation_year')->nullable();
            $table->integer('experience')->nullable();
            $table->string('study_field', 250)->nullable();
            $table->tinyInteger('deriving_licence')->nullable();
            $table->text('skills')->nullable();
            $table->text('languages')->nullable();
            $table->text('cv')->nullable();
            $table->text('audio')->nullable();
            $table->text('video')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('active')->nullable()->comment('null -> not active, 1 -> active');

            $table->string('birth')->nullable();

            $table->tinyInteger('gender')->nullable();
            $table->tinyInteger('block')->nullable()->comment('null->not bloked, 1->bloked'); 

            $table->string('socialite_id')->nullable();

            $table->text('token')->nullable();
            $table->timestamp('failed_at')->useCurrent();
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
        Schema::dropIfExists('employees');
    }
}
