<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirBookingPassengersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('air_booking_passengers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('air_booking_id');
            $table->string('title', 20)->nullable();
            $table->string('first_name', 150)->nullable();
            $table->string('surname', 150)->nullable();
            $table->string('type', 10)->nullable(); //ADT,CHD,INF
            $table->date('dob')->nullable();
            $table->string('gender', 10)->nullable(); // M,F
            $table->string('nationality_country', 100)->nullable();
            $table->string('passport_no', 20)->nullable();
            $table->string('passport_type', 20)->nullable();
            $table->string('passport_issuing_country', 100)->nullable();
            $table->date('passport_issuance_date', 100)->nullable();
            $table->date('passport_expiry_date', 100);
            $table->string('phone_no', 20)->nullable();
            $table->string('passport')->nullable();
            $table->string('visa')->nullable();

            $table->foreign('air_booking_id')->references('id')->on('air_bookings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('air_booking_passengers');
    }
}
