<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMultipleLanguageToQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('question_ch')->after('question')->nullable();
            $table->string('question_es')->after('question_ch')->nullable();
            $table->string('question_fr')->after('question_es')->nullable();
            $table->string('question_de')->after('question_fr')->nullable();
            $table->string('question_it')->after('question_de')->nullable();
            $table->string('question_ru')->after('question_it')->nullable();
            $table->string('question_pt')->after('question_ru')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            //
        });
    }
}
