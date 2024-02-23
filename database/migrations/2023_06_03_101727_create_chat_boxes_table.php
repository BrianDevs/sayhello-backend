<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_boxes', function (Blueprint $table) {
            $table->id();
            $table->integer('my_id');
            $table->integer('stranger_id');
            $table->dateTime('dateTime')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0-new , 1-accept_from_one_side, 2-accept_from__both_side, 3-denied');
            $table->softDeletes();
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
        Schema::dropIfExists('chat_boxes');
    }
}
