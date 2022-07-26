<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('air_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('air_booking_id'); // BelongsTo Booking
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->string('confirmation_id', 100)->nullable(); # Airline PNR
            $table->double('amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('BDT');
            $table->string('status', 100)->default(\App\Enums\FlightEnum::STATUS['PENDING']);
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->timestamps();

            $table->foreign('air_booking_id')->references('id')->on('air_bookings');
            $table->foreign('agent_id')->references('id')->on('agents');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('issued_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('air_tickets');
    }
}
