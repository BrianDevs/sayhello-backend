<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function addPets(Request $request)
    {
        Pet::create($request->all());
        return response()->json(['status'=>true , 'message'=>'Pet added']);
    }
    public function getPets()
    {
        $Pets = Pet::where('is_active', 0)->whereNull('deleted_at')->select('id', 'name')->get();
        if(count($Pets)> 0){
            return response()->json(['status'=>true, 'message'=>'Get pets', 'data'=>$Pets]);
        }else{
            return response()->json(['status'=>false, 'message'=>'Not get pets']);
        }
    }
}
