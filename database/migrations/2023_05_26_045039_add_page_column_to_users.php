<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPageColumnToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('page')->after('is_active')->default(0)->comment('0-new, 1-sibscription, 2-update u&b docs, 3-upload image, 4-personal info, 5-lifestyle, 6-pro_details, 7-my search, 8-want_to_like, 9-want_to_dislike, 10-moreinfo, 11-home');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
