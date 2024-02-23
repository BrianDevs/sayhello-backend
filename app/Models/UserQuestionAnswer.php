<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserQuestionAnswer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $date = 'deleted_at';
    protected $table = 'user_question_answers';
    protected $fillable = ['user_id', 'question_id' , 'answer' , 'deleted_at'];
}
