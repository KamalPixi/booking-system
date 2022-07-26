<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrePurchasedAirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_purchased_airs', function (Blueprint $table) {
            $table->id();
            $table->string('airline');
            $table->string('from');
            $table->string('to');
            $table->double('fare', 12, 2)->default(0);
            $table->date('depart_date');
            $table->date('arrival_date')->nullable();
            $table->string('baggage')->nullable();
            $table->string('transit_location')->nullable();
            $table->string('transit_time')->nullable();
            $table->text('reference_remark')->nullable();
            $table->integer('count')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('manage_by')->nullable();
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
        Schema::dropIfExists('pre_purchased_airs');
    }
}
