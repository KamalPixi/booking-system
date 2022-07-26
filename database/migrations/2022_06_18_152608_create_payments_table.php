<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TransactionEnum;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('paymentable_type'); // BelongsTo Model(AirTicket/Ride)
            $table->unsignedBigInteger('paymentable_id'); // BelongsTo Model Id
            $table->unsignedBigInteger('agent_id'); // for ease of query
            $table->double('amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('BDT');
            $table->string('method', 50); // BALANCE_TRANSFER,CASH,ONLINE_BANK_TRANSFER,ONLINE
            $table->string('purpose'); // AIR_TICKET_PURCHASE, RIDE,
            $table->string('status', 50)->default(TransactionEnum::STATUS['PENDING']); // APPROVED,REJECTED,PROCESSING,PENDING
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('created_by'); // User Id
            $table->unsignedBigInteger('updated_by')->nullable(); // User Id, will be done by admin
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('payments');
    }
}
