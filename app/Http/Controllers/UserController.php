<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App;
use Session;

class UserController extends Controller
{
    public function index(Request $req)
    {
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }
        
        $data['title'] = __('bahasa.user');
        $data['header'] = 'goback';
        $data['menu'] = '';

        $response = Http::attach('token',Session::get('token')) 
        // ->attach('level_login',Session::get('level')) 
        ->withHeaders([ 
            'Authorization'=> api_token(),
        ]) 
        ->post(api_url().'api/v1/list_user');

        $dt_user = json_decode($response->body());

        $data['list_user'] = $dt_user;
        $data['list_user_code'] = $response->getStatusCode();

        return view('user.user',$data);
    }


    public function AddData(Request $req){
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }

        $data['title'] = __('bahasa.tambah_pengguna');
        $data['header'] = 'goback';

        // $response = Http::attach('token',Session::get('token'))
        // ->withHeaders([ 
        //     'Authorization'=> api_token(),
        // ]) 
        // ->post(api_url().'api/v1/list_level');

        // $data['list_level'] = json_decode($response->body());
        $data['detail_user'] = [];
        // $data['menu'] = '';

        $response_cabang = Http::attach('token',Session::get('token'))
        ->attach('type','add')
        ->withHeaders([ 
            'Authorization'=> api_token(),
        ]) 
        ->post(api_url().'api/v1/list_cabang_user');
        $data['list_cabang'] = json_decode($response_cabang->body());

        return view('user.user_tambah',$data);
    }


    public function EditData($id, Request $req){
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }

        $data['title'] = __('bahasa.edit_pengguna');
        $data['header'] = 'goback';

        $response = Http::attach('token',Session::get('token'))
        ->attach('id_user',$id)
        ->withHeaders([ 
            'Authorization'=> api_token(),
        ]) 
        ->post(api_url().'api/v1/detail_user'); 

        $data['detail_user'] = json_decode($response->body());

        $response = Http::attach('token',Session::get('token'))
        ->withHeaders([ 
            'Authorization'=> api_token(),
        ]) 
        ->post(api_url().'api/v1/list_level');

        $data['list_level'] = json_decode($response->body());

        $response_cabang = Http::attach('token',Session::get('token'))
        ->attach('type','edit')
        ->withHeaders([ 
            'Authorization'=> api_token(),
        ]) 
        ->post(api_url().'api/v1/list_cabang_user');
        $data['list_cabang'] = json_decode($response_cabang->body());
        

        return view('user.user_tambah',$data);
    }


    public function SaveData(Request $req)
    {
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }

        $response = Http::attach('token',Session::get('token'))
        ->attach('id_user',$req->id_user)
        ->attach('nama_user',$req->nama_user)
        ->attach('hp_user',$req->hp_user)
        ->attach('email_user',$req->email_user)
        ->attach('cabang_user',$req->cabang_user)
        ->attach('tanggal_lahir',$req->tanggal_lahir)
        ->withHeaders([ 
            'Authorization'=> api_token(),
        ]) 
        ->post(api_url().'api/v1/save_user'); 

        $result = json_decode($response->body());

        if($response->getStatusCode() == '200'){
            return redirect('/user')->with(['sukses' => __('bahasa.user').' '.$req->nama_user.' '.__('bahasa.notif_berhasil_disimpan')]);
        } else {
            return redirect('/user')->with(['error_msg' => $result->Message]);
        }

    }


    public function DeleteData($id, Request $req)
    {
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }

        $response = Http::attach('token',Session::get('token'))
        ->attach('id_user',$id)
        ->withHeaders([ 
            'Authorization'=> api_token(),
        ]) 
        ->post(api_url().'api/v1/hapus_user'); 

        $result = json_decode($response->body());

        if($response->getStatusCode() == '200'){
            return redirect('/user')->with(['sukses' => __('bahasa.user').' '.$req->nama_user.' '.__('bahasa.notif_berhasil_dihapus')]);
        } else {
            return redirect('/user')->with(['error_msg' => $result->Message]);
        }
    }


}
