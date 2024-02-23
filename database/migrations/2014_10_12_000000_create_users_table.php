<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->integer('otp')->default(0);
            $table->string('image')->nullable();
            $table->string('interests')->nullable();
            $table->text('about_me')->nullable();
            $table->tinyInteger('zodiac')->default(0);
            $table->tinyInteger('pets')->default(0);
            $table->tinyInteger('smoking')->default(0)->comment('1 = If The user has a smoking habit, 2 = If The user not has a smoking habit');
            $table->tinyInteger('alcohol')->default(0)->comment('1 = If The user has a alcohol habit  , 2 = If The user not has a alcohol habit');
            $table->string('hobbies', 60)->default(0);
            $table->string('job_title')->nullable();
            $table->string('company')->nullable();
            $table->string('collage')->nullable();
            $table->string('school')->nullable();
            $table->tinyInteger('interested_in')->default(0);
            $table->string('age_range')->nullable();
            $table->string('located_in')->nullable();
            $table->text('i_want_to')->nullable();
            $table->string('my_likes')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
