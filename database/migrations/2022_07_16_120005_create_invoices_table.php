<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoiceable_type');
            $table->unsignedBigInteger('invoiceable_id');
            $table->string('invoice_no');
            $table->json('services')->nullable();
            $table->json('totals')->nullable();
            $table->string('status')->default('PENDING');
            $table->json('to')->nullable();
            $table->json('for')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
