<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('expire_membership_date')->after('lng')->nullable();
            $table->tinyInteger('is_member')->after('expire_membership_date')->default(0)->comment('user not get membership = 0 , user get membership = 1');
            $table->string('front_doc_image')->after('is_member')->nullable();
            $table->string('back_doc_image')->after('front_doc_image')->nullable();
            $table->tinyInteger('is_user_varified')->after('back_doc_image')->default(0)->comment('user not varified = 0 , user varified =1');
            $table->tinyInteger('image_varified')->after('is_user_varified')->default(0);
            $table->tinyInteger('photo_option')->after('image_varified')->default(0)->comment('0 = not show my photo any one , 1 = show my photo to every one');
            $table->tinyInteger('device_type')->after('photo_option')->default(0)->comment('0 = ios , 1 = android');
            $table->string('device_token')->after('device_type')->nullable();
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
