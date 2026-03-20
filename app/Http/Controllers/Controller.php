<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;

use DB;
use Session;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function api_token(Request $req)
    {
        return api_token();
    }

    public function GetProvinsi(Request $req)
    {
        $dtProvinsi = DB::table('tb_ro_provinces')->orderBy('province_name')->get();
        return $dtProvinsi;
    }

    public function GetKota(Request $req)
    {
        $dtKota = DB::table('tb_ro_cities')->where('province_id', $req->id_provinsi)->orderBy('city_name')->get();
        return $dtKota;
    }

    public function GetKecamatan(Request $req)
    {
        $dtKota = DB::table('tb_ro_subdistricts')->where('city_id', $req->id_kota)->orderBy('subdistrict_name')->get();
        return $dtKota;
    }

    public function getDataToko()
    {
        $token_penjual = Session::get('token');
        // dd($token_penjual);
        $cekPenjualKonsumen = DB::table('rb_konsumen')->where('remember_token', $token_penjual)->first();
        if(!$cekPenjualKonsumen){
            Session::flush();
            return redirect('login');
        }
        $dtPenjual = DB::table('rb_reseller')->where('id_konsumen', $cekPenjualKonsumen->id_konsumen)->first();
        // if(!$dtPenjual){
        //     Session::flush();
        //     return redirect('/login')->with(['error_msg' => 'Tidak Terdaftar Sebagai Admin Toko']);    
        // }
        
        return $dtPenjual;
    }
}
