<?php

namespace App\Http\Controllers;

use App\Models\Zodiac;
use Illuminate\Http\Request;

class ZodiacController extends Controller
{
    public function addZodiac(Request $request)
    {
        Zodiac::create($request->all());
        return response()->json(['status'=>true , 'message'=>'Zodiac added']);
    }
    public function getZodiac()
    {
        $Zodiac = Zodiac::where('is_active', 0)->whereNull('deleted_at')->select('id', 'name')->get();
        if(count($Zodiac)> 0) {
            return response()->json(['status'=>true , 'message'=>'Get zodiacs', 'data'=>$Zodiac]);
        }else{
            return response()->json(['status'=>false , 'message'=>'Not get zodiacs']);
        }
    }
}
