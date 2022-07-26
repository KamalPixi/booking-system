<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('fee_key');
            $table->string('fee_key_sub')->nullable(); # BS,OD,BG,MY(Airlines codes)
            $table->double('fee', 12, 2)->default(0);
            $table->string('type'); # percentage/fixed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_fees');
    }
}
