<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('couples', function (Blueprint $table) {
            $table->id();
            $table->integer('my_id');
            $table->integer('stranger_id');
            $table->string('selfie')->nullable();
            $table->string('accepted_lat')->nullable()->comment('The latitude of the person who accepts this.');
            $table->string('accepted_lng')->nullable()->comment('The longitude of the person who accepts this.');
            $table->tinyInteger('status')->default(0)->comment('0-new, 1-accept');
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
        Schema::dropIfExists('couples');
    }
}
