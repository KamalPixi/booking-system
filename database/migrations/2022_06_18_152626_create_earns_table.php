<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEarnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('earns', function (Blueprint $table) {
            $table->id();
            $table->string('earnable_type'); // BelongsTo Model(Ride/Affiliate)
            $table->unsignedBigInteger('earnable_id'); // BelongsTo Model Id
            $table->double('amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('BDT');
            $table->string('source', 100); // RIDE,AFFILIATE
            $table->unsignedBigInteger('earned_by'); // BelongsTo User

            $table->timestamps();
            $table->foreign('earned_by')->references('id')->on('users');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('earns');
    }
}
