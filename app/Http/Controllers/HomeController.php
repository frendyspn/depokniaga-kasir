<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App;
class HomeController extends Controller
{
    public function index(Request $req)
    {
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }
        
        $data['toko'] = $this->getDataToko();
        $data['title'] = __('bahasa.Beranda');
        // $data['header'] = 'goback';
        $data['menu'] = '';
        
        if($data['toko'] == null){
            Session::flush();
            return redirect('/login')->with(['error_msg' => 'Tidak Terdaftar Sebagai Admin Toko']);    
        }

        return view('home',$data);
    }
}
