<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class TestController extends Controller
{
    public function index()
    {
        //dd(1);
        $var=[];
        return response()->json([
            'status'=>true,           
            'data'=>$var,
            'msg'=>'Success or Error'
        ]);
    }
}