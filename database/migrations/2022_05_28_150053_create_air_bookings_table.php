<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('air_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('air_bookingable_type'); // BookedBy Model(User/Agent)
            $table->unsignedBigInteger('air_bookingable_id'); // BookedBy Model Id
            $table->string('reference', 50);
            $table->string('confirmation_id', 50);
            $table->string('trip_type', 50)->nullable();
            $table->string('airline_confirmation_id', 50)->nullable();
            $table->json('request_json')->nullable();
            $table->json('response_json')->nullable();
            $table->json('get_booking_response_json')->nullable();
            $table->json('flight_json')->nullable();
            $table->string('currency', 5)->default('BDT');
            $table->double('amount', 12, 2)->default(0);
            $table->double('amount_with_margin', 12, 2)->default(0);
            $table->string('payment_status', 100)->nullable(0);
            $table->string('ticketing_last_datetime')->nullable(); # since api sends 02-06(02Feb)
            $table->string('status', 100)->nullable();
            $table->string('source_api');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('air_bookings');
    }
}
