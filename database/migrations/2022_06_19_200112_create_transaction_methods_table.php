<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_methods', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('remark')->nullable(); # for account details
            $table->string('key'); // Constant ex: BKASH,NAGADH,ONLINE-TRANSFER
            $table->double('fee', 12, 2);
            $table->string('fee_type'); // FIXED/PERCENTAGE
            $table->boolean('status'); // 1/0
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_methods');
    }
}
