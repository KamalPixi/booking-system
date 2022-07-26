<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TransactionEnum;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transactionable_type'); // BelongsTo action Model(AirTicket/Payment/Deposit/Earn/Withdrawal/Refund)
            $table->unsignedBigInteger('transactionable_id');
            $table->string('sign', 1)->default(TransactionEnum::SIGN['PLUS']); // on user Account Balance Perspective
            $table->double('amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('BDT');
            $table->string('purpose'); // AIR_TICKET_PURCHASE_PARTIAL, AIR_TICKET_PURCHASE_FULL, ACCOUNT_TOPUP,
            $table->string('method', 100); // CASH,ONLINE_BANK_TRANSFER,ONLINE
            $table->unsignedBigInteger('initiated_by'); // User Id
            $table->string('remark')->nullable();
            $table->timestamps();

            $table->foreign('initiated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
