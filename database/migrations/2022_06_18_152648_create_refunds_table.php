<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->string('refundable_type');
            $table->unsignedBigInteger('refundable_id');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('refunded_by')->nullable();
            $table->double('amount', 12, 2)->nullable()->default(0);
            $table->double('fee', 12, 2)->nullable()->default(0);
            $table->string('currency', 50)->default(App\Enums\TransactionEnum::CURRENCY['BDT']);
            $table->string('refund_method')->nullable();
            $table->string('remark')->nullable();
            $table->string('admin_remark')->nullable();
            $table->string('status')->default(App\Enums\TransactionEnum::STATUS['PENDING']);
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('agents');
            $table->foreign('requested_by')->references('id')->on('users');
            $table->foreign('refunded_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refunds');
    }
}
