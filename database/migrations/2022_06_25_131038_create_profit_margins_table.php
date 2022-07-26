<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfitMarginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profit_margins', function (Blueprint $table) {
            $table->id();
            $table->string('profit_marginable_type');
            $table->unsignedBigInteger('profit_marginable_id');
            $table->string('key');
            $table->string('type', 50);
            $table->double('amount', 12, 2);
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
        Schema::dropIfExists('profit_margins');
    }
}
