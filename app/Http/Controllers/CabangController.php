<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App;
use DB;
use Illuminate\Support\Facades\Http;

class CabangController extends Controller
{
    public function index(Request $req)
    {
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }
        
        $data['title'] = __('bahasa.cabang');
        $data['header'] = 'goback';
        $data['menu'] = '';

        $response = Http::attach('token',Session::get('token'))
        ->withHeaders([ 
            'Authorization'=> api_token(),
        ]) 
        ->post(api_url().'api/v1/list_cabang'); 

        $dt_cabang = json_decode($response->body());

        // $dt_user = DB::table('rb_konsumen')->where('remember_token', $req->token)->first();
        // $dt_cabang = DB::table('rb_reseller as a')->select('a.*', 'b.subdistrict_name as kecamatan', 'c.city_name as kota')->rightJoin('tb_ro_subdistricts as b', 'b.subdistrict_id', 'a.kecamatan_id')->rightJoin('tb_ro_cities as c', 'c.city_id', 'a.kota_id')->where('a.id_user_pos', $dt_user->id)->get();


        $data['list_cabang'] = $dt_cabang ?? [];
        $data['list_cabang_code'] = 200;

        return view('cabang.cabang',$data);
    }

    public function AddData(Request $req){
        App::setLocale(session()->get('locale'));
        
        if (!cek_login()){
            return redirect('/login');
        }

        $data['title'] = __('bahasa.tambah_cabang');
        $data['header'] = 'goback';

        $data['detail_cabang'] = [];
        // $data['menu'] = '';

        return view('cabang.cabang_tambah',$data);
    }

    public function EditData($id, Request $req){

        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }

        $data['title'] = __('bahasa.edit_cabang');
        $data['header'] = 'goback';

        $response = Http::attach('token',Session::get('token'))
        ->attach('id_toko',$id)
        ->withHeaders([ 
            'Authorization'=> api_token(),
        ]) 
        ->post(api_url().'api/v1/detail_cabang'); 

        $data['detail_cabang'] = json_decode($response->body());

        

        return view('cabang.cabang_tambah',$data);
    }

    public function SaveData(Request $req)
    {
        App::setLocale(session()->get('locale'));
        
        if (!cek_login()){
            return redirect('/login');
        }

        // $response = Http::attach('token',Session::get('token'))
        // ->attach('id_toko',$req->id_toko)
        // ->attach('nama_toko',$req->nama_toko)
        // ->attach('deskripsi_toko',$req->deskripsi_toko)
        // ->attach('nomor_hp_toko',$req->nomor_hp_toko)
        // ->attach('provinsi_toko',$req->provinsi_toko)
        // ->attach('kota_toko',$req->kota_toko)
        // ->attach('kecamatan_toko',$req->kecamatan_toko)
        // ->attach('alamat_toko',$req->alamat_toko)
        // ->attach('jenis_toko',$req->jenis_toko)
        // ->withHeaders([ 
        //     'Authorization'=> api_token(),
        // ]) 
        // ->post(api_url().'api/v1/save_cabang'); 

        // $result = json_decode($response->body());

        try {
            $dt_user = DB::table('rb_konsumen')->where('remember_token', Session::get('token'))->first();
            $user = $dt_user->id_konsumen;

            $dtInsert['id_konsumen'] = $user;
            $dtInsert['nama_reseller'] = $req->nama_toko;
            $dtInsert['kecamatan_id'] = $req->kecamatan_toko;
            $dtInsert['kota_id'] = $req->kota_toko;
            $dtInsert['provinsi_id'] = $req->provinsi_toko;
            $dtInsert['alamat_lengkap'] = $req->alamat_toko;
            $dtInsert['no_telpon'] = $req->nomor_hp_toko;
            $dtInsert['keterangan'] = $req->deskripsi_toko;
            $dtInsert['jenis_toko'] = $req->jenis_toko;
            $dtInsert['kordinat'] = $req->kordinat_toko;
            $dtInsert['tanggal_daftar'] = date('Y-m-d H:i:s');
            $dtInsert['verifikasi'] = 'Y';


            if ($req->id_toko != '-') {
                $dtCabang = DB::table('rb_reseller')->where('id_reseller', $req->id_toko)->update($dtInsert);
            } else {
                $dtCabang = DB::table('rb_reseller')->insert($dtInsert);
            }

            return redirect('/home')->with(['sukses' => __('bahasa.cabang').' '.$req->nama_toko.' '.__('bahasa.notif_berhasil_disimpan')]);
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            return redirect('/store_register')->with(['error_msg' => $th->getMessage()]);
        }

    }

    public function DeleteData($id, Request $req)
    {
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }

        $response = Http::attach('token',Session::get('token'))
        ->attach('id_toko',$id)
        ->withHeaders([ 
            'Authorization'=> api_token(),
        ]) 
        ->post(api_url().'api/v1/hapus_cabang'); 

        $result = json_decode($response->body());

        if($response->getStatusCode() == '200'){
            return redirect('/cabang')->with(['sukses' => __('bahasa.cabang').' '.$req->nama_toko.' '.__('bahasa.notif_berhasil_dihapus')]);
        } else {
            return redirect('/cabang')->with(['error_msg' => $result->Message]);
        }
    }

    public function NoStore(Request $req){
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }

        $data['title'] = 'Belum Memiliki Toko';
        $data['header'] = '';

        return view('cabang.no_store',$data);
    }




    // STORE

    public function AddStore(Request $req){
        App::setLocale(session()->get('locale'));
        
        if (!cek_login()){
            return redirect('/login');
        }

        $this->generateOwnerToko();

        $data['title'] = 'Daftarkan Toko';
        $data['header'] = 'goback';

        $data['detail_cabang'] = [];
        // $data['menu'] = '';

        return view('cabang.toko_tambah',$data);
    }

    private function generateOwnerToko()
    {
        $token_penjual = Session::get('token');
       
        $cekPenjualKonsumen = DB::table('rb_konsumen')->where('remember_token', $token_penjual)->first();
        if(!$cekPenjualKonsumen){
            Session::flush();
            return redirect('login');   
        }

        $dtPos['name'] = $cekPenjualKonsumen->nama_lengkap;
        $dtPos['no_hp'] = $cekPenjualKonsumen->no_hp;
        $dtPos['email'] = $cekPenjualKonsumen->email;
        $dtPos['password'] = $cekPenjualKonsumen->password;
        $dtPos['remember_token'] = $cekPenjualKonsumen->remember_token;
        // $dtPos['otp'] = rand(100000,999999);
        // $dtPos['otp_expired'] = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        $dtPos['level'] = 'owner';
        $dtPos['parent'] = $cekPenjualKonsumen->id_konsumen;

        try {
            $insertPosUser = DB::table('pos_user')->insertGetId($dtPos);

            DB::table('rb_konsumen')->where('id_konsumen', $cekPenjualKonsumen->id_konsumen)->update(['id_pos_user' => $insertPosUser]);

            return $insertPosUser;
        } catch (\Exception $e) {
            // Handle exception if needed
            return null;
        }

    }
}
