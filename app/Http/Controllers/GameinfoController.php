<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gameinfo;
class GameinfoController extends Controller
{
    public function getinfo(Request $request){
        $type=$request->type;
        $data=Gameinfo::where('type',$type)->get();
        return response()->json($data);

    }
    public function getoneinfo($id){
        $data=Gameinfo::where('id',$id)->get();
        return response()->json($data);
    }
    public function getimage($name){
        $path = public_path().'/uploads/images/'.$name;
        return response()->download($path);      
       
    }
}

