<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use Illuminate\Http\Request;

class HobbyController extends Controller
{
    public function addHobbies(Request $request)
    {
        Hobby::create($request->all());
        return response()->json(['status'=>true , 'message'=>'Hobbies added']);
    }
     public function getHobbies()
    {
        $Hobbies = Hobby::where('is_active', 0)->whereNull('deleted_at')->select('id', 'name')->get();
        if(count($Hobbies)> 0){
            return response()->json(['status'=>true, 'message'=>'Get Hobbies', 'data'=>$Hobbies]);
        }else{
            return response()->json(['status'=>false, 'message'=>'Not get Hobbies']);
        }
    }
}
