<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ApiLog,User};
use Http;

class HelperController extends Controller
{
    public function getSSARoleUserId(){
        $url = config('constant.ssa_url').'/api/get_role_user?role_id=15';
        $header = [
            'content-type' => 'application/json; charset=UTF-8'
        ];
        $parameter = [];
        $response = Http::withHeaders($header)->get($url);
        $log = ApiLog::create([
            'url'=>$url,
            'parameter'=>json_encode($parameter,JSON_UNESCAPED_UNICODE),
            'header'=>json_encode($header,JSON_UNESCAPED_UNICODE),
            'response'=>collect(json_decode($response,true))
        ]);
        $emails = json_decode($response,true);
        return $users = User::whereIn('email',$emails)->get();
    }
}
