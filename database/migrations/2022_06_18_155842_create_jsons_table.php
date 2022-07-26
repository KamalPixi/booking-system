<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJsonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jsons', function (Blueprint $table) {
            $table->id();
            $table->string('jsonable_type');
            $table->unsignedBigInteger('jsonable_id');
            $table->string('type'); // REQUEST,RESPONSE,GET_RESPONSE
            $table->string('type_key')->nullable(); // BOOKING_CANCEL, TICKET_VOID
            $table->json('json');
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
        Schema::dropIfExists('jsons');
    }
}
