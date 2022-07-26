<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TransactionEnum;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('BDT');
            $table->string('method', 50); // CASH,ONLINE_BANK_TRANSFER,ONLINE
            $table->string('status', 50)->default(TransactionEnum::STATUS['PENDING']); // APPROVED,REJECTED,PROCESSING,PENDING
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('requested_by'); // User Id
            $table->unsignedBigInteger('updated_by')->nullable(); // User Id, will be done by admin
            $table->timestamps();
            
            $table->foreign('requested_by')->references('id')->on('users');
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
        Schema::dropIfExists('withdrawals');
    }
}
