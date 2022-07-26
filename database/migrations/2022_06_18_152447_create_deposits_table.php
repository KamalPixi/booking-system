<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\TransactionEnum;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->string('depositable_type'); // BelongsTo Model (User/Agent)
            $table->unsignedBigInteger('depositable_id'); // BelongsTo Model id
            $table->string('transaction_no', 100);
            $table->double('amount', 12, 2)->default(0);
            $table->double('fee', 12, 2)->default(0);
            $table->string('currency', 10)->default('BDT');
            $table->string('method', 50); // CASH,ONLINE_BANK_TRANSFER,ONLINE
            $table->string('status', 50)->default(TransactionEnum::STATUS['PENDING']); // APPROVED,REJECTED,PROCESSING,PENDING
            $table->text('remark')->nullable();
            $table->text('admin_remark')->nullable();
            $table->unsignedBigInteger('deposited_admin_bank_account_id')->nullable();
            $table->unsignedBigInteger('deposited_by'); // User Id
            $table->unsignedBigInteger('updated_by')->nullable(); // User Id, will be done by admin
            $table->timestamps();
            
            $table->foreign('deposited_by')->references('id')->on('users');
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
        Schema::dropIfExists('deposits');
    }
}
