<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingPartialRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_partial_requests', function (Blueprint $table) {
            $table->id();
            $table->string('booking_requestable_type');
            $table->unsignedBigInteger('booking_requestable_id');
            $table->string('status')->default(\App\Enums\TransactionEnum::STATUS['PENDING']);
            $table->double('approved_amount', 12, 2)->default(0);
            $table->boolean('is_used', 12, 2)->default(0);
            $table->timestamp('due_time')->nullable();
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();

            $table->foreign('requested_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_partial_requests');
    }
}
