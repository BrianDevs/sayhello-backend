<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function addQuestions(Request $request)
    {
        Question::create($request->all());
        return response()->json(['status'=>true , 'message'=>'Question added']);
    }
    public function getQuestions(Request $request)
    {
        $pageNumber = $request->limit; // The page number to retrieve the data
        $perPage = 10; // Number of records per page
        $skip = ($pageNumber - 1) * $perPage; // Calculate the number of records to skip

        $userId = $request->user_id; // User ID for filtering

        
        $Questions = Question::leftJoin('user_question_answers', function ($join) use ($userId) {
                                    $join->on('user_question_answers.question_id', '=', 'questions.id')
                                        ->where('user_question_answers.user_id', '=', $userId);
                                })
                                ->select('questions.id', 'questions.question', 'user_question_answers.id as answer_id', 'user_question_answers.deleted_at as deleted','user_question_answers.answer')
                                ->skip($skip)
                                ->take($perPage)
                                ->where('is_active', 0)
                                ->whereNull('questions.deleted_at')
                                ->orderBy('questions.id')
                                ->get();
        if(count($Questions)> 0){
            foreach($Questions as $Question){
                if($Question->deleted == 'null' || $Question->deleted === null){
                    // $Question->answer_id = null;
                    // $Question->answer = null;
                }else{
                    $Question['answer_id'] = null;
                    $Question['answer'] = null;
                }
            }
            return response()->json(['status'=>true , 'message'=>'Get Questions' , 'data'=>$Questions]);
        }else{
            return response()->json(['status'=>false , 'message'=>'Get Not Questions']);
        }
    }
    public function deleteQ($id)
    {
        $country = Question::find($id);
        $country->userQuestionAnswer()->delete();
        $country->delete();   
    }
}
