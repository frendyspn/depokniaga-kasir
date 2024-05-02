<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Session;
use App;

class LoginController extends Controller
{
    public function index()
    {
        App::setLocale(session()->get('locale'));

        if (cek_login()){
            return redirect('/home');
        }
        
        $data['title'] = __('bahasa.Masuk');
        // $data['header'] = 'goback';

        return view('login',$data);
    }


    public function prosesLogin(Request $req)
    {
        App::setLocale(session()->get('locale'));
        $validated = $req->validate([
            'nomor_hp' => 'required|numeric',
        ]);

        $no_hp = $req->nomor_hp;
        $type_login = $req->type_login;
        $response = Http::attach('username',$no_hp) 
            ->attach('type_login',$type_login) 
            ->withHeaders([ 
                'Authorization'=> api_token(),
            ]) 
            ->post(api_url().'api/login'); 
        $result = json_decode($response->body());

        if($response->getStatusCode() == '200'){
            return redirect('/otp')->with(['nomor' => $no_hp, 'level' => $type_login, 'otp' => 'Ini Hanya Untuk Development, OTP Login '.$result->Message]);
            // return redirect('/otp')->with(['nomor' => $no_hp, 'level' => $type_login, 'otp' => 'Kode OTP Sudah Dikirim']);
        } else {
            return redirect('/login')->with(['error_msg' => __('bahasa.'.$result->Message)]);
        }

    }


    public function otpLogin(Request $req)
    {
        App::setLocale(session()->get('locale'));

        if (Session::get('nomor') == '') {
            return redirect('/login')->with(['error_msg' => __('bahasa.Terjadi_Kesalahan_Silahkan_Ulangi_Lagi')]);
        }

        $data['title'] = 'OTP';
        $data['header'] = 'goback';

        return view('otp',$data);
    }


    public function prosesOtp(Request $req)
    {
        App::setLocale(session()->get('locale'));

        $validated = $req->validate([
            'nomor_hp' => 'required|numeric',
            'otp' => 'required|numeric',
        ]);

        $response = Http::attach('username',$req->nomor_hp)
        ->attach('otp',$req->otp) 
        ->attach('type_login',$req->type_login) 
        ->withHeaders([ 
            'Authorization'=> api_token(),
        ]) 
        ->post(api_url().'api/verifikasi_otp'); 

        $result = json_decode($response->body());

        if($response->getStatusCode() == '200'){
            Session::put('token', $result->token);
            Session::put('nama_lengkap', $result->nama_lengkap);
            Session::put('email', $result->email);
            Session::put('no_hp', $result->no_hp);
            Session::put('level', $result->level);

            Session::put('keranjang', array());
            return redirect('/home')->with(['sukses' => __('bahasa.selamat_datang').' '.$result->nama_lengkap]);
        } else {
            return redirect('/login')->with(['error_msg' => $result->Message]);
        }
    }

    public function logout(){
        Session::flush();
        return redirect('login');
    }
}
