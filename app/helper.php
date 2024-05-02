<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
// use Illuminate\Session\SessionManager;
// use Illuminate\Support\Facades\Session;

// use DB;

if (!function_exists('api_token')) {
    function api_token(){
        return 'Bearer '.Hash::make(date('Ymd').'#SATUKASIR');
    }
}

if (!function_exists('api_url')) {
    function api_url(){
        // return 'http://localhost/apisatutoko/';
        return 'https://api.satutoko.id/';
    }
}


if (!function_exists('cek_login')) {
    function cek_login(){
        if (Session::has('token')){
            $token = Session::get('token');
            $no_hp = Session::get('no_hp');
        } else {
            // Session::flush();
            return false;
        }

        $response = Http::attach('username',$no_hp)
        ->attach('token',$token) 
        ->withHeaders([ 
            'Authorization'=> api_token(),
        ]) 
        ->post(api_url().'api/cek_login');

        if($response->getStatusCode() == '200'){
            if (Session::has('token') && Session::get('token') != '') {
                return true;
            } else {
                Session::flush();
                return false;
            }
            
        } else {
            Session::flush();
            return false;
        }
    }
}

if (!function_exists('hari_ind')) {
    function hari_ind($day){
        if ($day == 'Sunday') {
            return 'Minggu';
        } else if ($day == 'Monday') {
            return 'Senin';
        } else if ($day == 'Tuesday') {
            return 'Selasa';
        } else if ($day == 'Wednesday') {
            return 'Rabu';
        } else if ($day == 'Thursday') {
            return 'Kamis';
        } else if ($day == 'Friday') {
            return 'Jumat';
        } else if ($day == 'Saturday') {
            return 'Sabtu';
        } else {
            return '';
        }
        
    }
}
