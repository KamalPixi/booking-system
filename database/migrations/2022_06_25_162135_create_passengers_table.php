<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassengersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->string('title');
            $table->string('first_name');
            $table->string('surname');
            $table->string('type');
            $table->string('phone_no')->nullable();
            $table->string('email')->nullable();
            $table->date('dob');
            $table->string('gender');
            $table->string('nationality_country')->default('BD');
            $table->string('passport_no');
            $table->string('passport_issuing_country')->default('BGD');
            $table->date('passport_issuance_date')->nullable();
            $table->date('passport_expiry_date');
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('agents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passengers');
    }
}
