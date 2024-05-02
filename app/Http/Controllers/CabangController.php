<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App;
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

        $data['list_cabang'] = $dt_cabang;
        $data['list_cabang_code'] = $response->getStatusCode();

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

        $response = Http::attach('token',Session::get('token'))
        ->attach('id_toko',$req->id_toko)
        ->attach('nama_toko',$req->nama_toko)
        ->attach('deskripsi_toko',$req->deskripsi_toko)
        ->attach('nomor_hp_toko',$req->nomor_hp_toko)
        ->attach('provinsi_toko',$req->provinsi_toko)
        ->attach('kota_toko',$req->kota_toko)
        ->attach('kecamatan_toko',$req->kecamatan_toko)
        ->attach('alamat_toko',$req->alamat_toko)
        ->withHeaders([ 
            'Authorization'=> api_token(),
        ]) 
        ->post(api_url().'api/v1/save_cabang'); 

        $result = json_decode($response->body());

        if($response->getStatusCode() == '200'){
            return redirect('/cabang')->with(['sukses' => __('bahasa.cabang').' '.$req->nama_toko.' '.__('bahasa.notif_berhasil_disimpan')]);
        } else {
            return redirect('/cabang')->with(['error_msg' => $result->Message]);
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
}
