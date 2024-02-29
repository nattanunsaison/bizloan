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
        $emails = array_filter(json_decode($response,true));
        $emails = ['paopan@siamsaison.com'];
        // dd($emails);
        return $users = User::whereIn('email',$emails)->get();
    }

    public function dateThai($date){ //format YYYYMMDD
        $actual_date = \Carbon\Carbon::parse($date);
        $day_month = $actual_date->copy()->locale('th_TH')->isoFormat('DD MMMM');
        $year = $actual_date->copy()->isoFormat('YYYY') + 543;
        return $day_month.' '.$year;
    }


    function currencyThai($amount_number){
        $amount_number = number_format($amount_number, 2, ".","");
        $pt = strpos($amount_number , ".");
        $number = $fraction = "";
        if ($pt === false) 
            $number = $amount_number;
        else
        {
            $number = substr($amount_number, 0, $pt);
            $fraction = substr($amount_number, $pt + 1);
        }
        
        $ret = "";
        $baht = $this->ReadNumber ($number);
        if ($baht != "")
            $ret .= $baht . "บาท";
        
        $satang = $this->ReadNumber($fraction);
        if ($satang != "")
            $ret .=  $satang . "สตางค์";
        else 
            $ret .= "ถ้วน";
        return $ret;
    }

    function ReadNumber($number){
        $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
        $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
        $number = $number + 0;
        $ret = "";
        if ($number == 0) return $ret;
        if ($number > 1000000)
        {
            $ret .= $this->ReadNumber(intval($number / 1000000)) . "ล้าน";
            $number = intval(fmod($number, 1000000));
        }
        
        $divider = 100000;
        $pos = 0;
        while($number > 0)
        {
            $d = intval($number / $divider);
            $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : 
                ((($divider == 10) && ($d == 1)) ? "" :
                ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
            $ret .= ($d ? $position_call[$pos] : "");
            $number = $number % $divider;
            $divider = $divider / 10;
            $pos++;
        }
        return $ret;
    }
}
