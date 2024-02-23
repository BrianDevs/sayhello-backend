<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'questions';
    protected $date  = 'deleted_at';
    protected $fillable = ['question', 'question_es', 'question_fr', 'question_de', 'question_ch' ,'question_it' , 'question_ru' , 'question_pt' ,  'is_active'];

    public function userQuestionAnswer()
    {
        return $this->hasMany(UserQuestionAnswer::class);
    }
}
