<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Session;
use DB;
use App;

class PosController extends Controller
{
    public function index(Request $req)
    {
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }

        $data['title'] = 'Pos';
        $data['header'] = 'goback';
        $data['menu'] = '';

        // $response = Http::attach('token',Session::get('token')) 
        // ->attach('id_toko',$this->getDataToko()->id_reseller)
        // ->withHeaders([ 
        //     'Authorization'=> api_token(),
        // ]) 
        // ->post(api_url().'api/v1/list_barang'); 

        $id_toko = $this->getDataToko()->id_reseller;

        $data['dtBarang'] = DB::table('rb_produk as a')
        // ->select('a.id_produk', 'a.nama_produk', 'a.harga_reseller', 'a.harga_konsumen', 'a.harga_premium','a.harga_beli', 'a.berat','a.gambar','a.satuan', 'b.nama_kategori')
        ->select('a.*', 'b.nama_kategori', 'c.diskon')
        ->leftJoin('rb_kategori_produk as b', 'b.id_kategori_produk', 'a.id_kategori_produk')
        ->leftJoin('rb_produk_diskon as c', 'c.id_produk', 'a.id_produk')
        ->where('a.id_reseller', $id_toko)
        ->where('a.aktif', 'Y')->get();
        // return array_column(json_decode($data['dtBarang']), 'id_produk');

        $data['dtVariasi'] = DB::table('rb_produk_variasi as a')
        ->select('a.*')
        ->whereIn('a.id_produk', array_column(json_decode($data['dtBarang']), 'id_produk'))->orderBy('a.id_produk')->get();

        $data['dtKategori'] = DB::table('rb_kategori_produk as a')
        ->select('a.*')
        ->orderBy('a.nama_kategori')->get();

        $data['dtTerlaris'] = DB::table('rb_penjualan_detail as b')
        ->select('a.id_produk', 'a.nama_produk', 'a.harga_reseller', 'a.harga_konsumen', 'a.harga_premium','a.harga_beli', 'a.berat','a.gambar', 'a.id_kategori_produk', 'a.source')
        ->rightJoin('rb_produk as a', 'a.id_produk', 'b.id_produk')
        ->where('a.aktif', 'Y')
        ->where('a.id_reseller', $id_toko)
        ->groupBy('a.id_produk', 'a.nama_produk', 'a.harga_reseller', 'a.harga_konsumen', 'a.harga_premium','a.harga_beli', 'a.berat','a.gambar', 'a.id_kategori_produk', 'a.source')
        ->orderBy(DB::Raw('sum(b.jumlah)'), 'DESC')
        ->get();
    

        // $data['list_barang'] = json_decode($response->body())->dtBarang;
        // $data['list_barang_code'] = $response->getStatusCode();

        // if($response->getStatusCode() == '200'){
            $data['list_kategori'] = json_decode($data['dtKategori']);
    
            Session::put('list_barang', json_decode($data['dtBarang']));
            Session::put('list_variasi', json_decode($data['dtVariasi']));
            Session::put('list_kategori', json_decode($data['dtKategori']));
            Session::put('list_terlaris', json_decode($data['dtTerlaris']));
        // } else {
        //     $data['list_kategori'] = [];
        // }

        // dd(Session::get('list_barang'));

        return view('pos.pos',$data);
    }

    public function ViewListBarang(Request $req)
    {
        App::setLocale(session()->get('locale'));

        $list_barang = Session::get('list_barang');
        $list_terlaris = Session::get('list_terlaris');
        
        if ($req->kategori != '') {
            $kategori = $req->kategori;
            $list_barang = array_values(array_filter($list_barang, function ($var) use ($kategori) {
                return ($var->id_kategori_produk == $kategori);
            }));
            // $list_terlaris = array_values(array_filter($list_terlaris, function ($var) use ($kategori) {
            //     return ($var->id_kategori_produk == $kategori);
            // }));
        }

        if ($req->nama_barang != ''){
            $nama_barang = $req->nama_barang;
            $list_barang = array_values(array_filter($list_barang, function ($var) use ($nama_barang) {
                if (stripos($var->nama_produk, $nama_barang) !== false) {
                    return true;
                }
            }));
        }

        if ($req->barcode != ''){
            $barcode = $req->barcode;
            $list_barang = array_values(array_filter($list_barang, function ($var) use ($barcode) {
                if (stripos($var->sku, $barcode) !== false) {
                    return true;
                }
            }));
        }

        $tmp = '<span>'.__('bahasa.daftar_belanja').'</span>';
        for($i=0; $i < count($list_barang); $i++){
            $tmp .= '
            <div class="splide__slide col mb-2" style="min-width:100px; max-width:150px">
                <a href="#" onclick="tambahKeranjang('.$list_barang[$i]->id_produk.')">
                    <div class="user-card">';
                    if($list_barang[$i]->gambar == '') {
                        $tmp .='<img src="'.asset('assets/img/sample/avatar/avatar2.jpg').'" alt="img" class="imaged w-100">';
                    }else{
                        $arrGambar = explode(';', $list_barang[$i]->gambar);
                        // $tmp .= '<img src="https://satutoko.id/asset/foto_produk/'.$arrGambar[0].'" style="width=83.66px; height:83.66px"  alt="img" class="imaged w-100">';
                        if ($list_barang[$i]->source == 'POS') {
                            $tmp .= '<img src="'.env('ADMIN_URL').'asset/foto_produk/'.$arrGambar[0].'" style="width=83.66px; height:83.66px"  alt="img" class="imaged w-100">';
                        } else {
                            $tmp .= '<img src="'.env('ADMIN_URL').'asset/foto_produk/'.$arrGambar[0].'" style="width=83.66px; height:83.66px"  alt="img" class="imaged w-100">';
                        }
                    }

                    if(strlen($list_barang[$i]->nama_produk) > 30) {
                        $nama_barang = substr($list_barang[$i]->nama_produk, 0, 30) . '...';
                    }else{
                        $nama_barang = $list_barang[$i]->nama_produk;
                    }
                                        
                    $tmp .= '<strong>'.$nama_barang.'</strong>
                    </div>
                </a>
            </div>';

        }

        $tmp .= '<span>Terlaris</span>';
        if (count($list_terlaris) >= 10) {
            $laris_max = 10;
        } elseif (count($list_terlaris) == 0) {
            $laris_max = 0;
        } else {
            $laris_max = count($list_terlaris);
        }
        for($i=0; $i < $laris_max; $i++){
            $tmp .= '
            <div class="splide__slide col mb-2" style="min-width:100px; max-width:150px">
                <a href="#" onclick="tambahKeranjang('.$list_terlaris[$i]->id_produk.')">
                    <div class="user-card">';
                    if($list_terlaris[$i]->gambar == '') {
                        $tmp .='<img src="'.asset('assets/img/sample/avatar/avatar2.jpg').'" alt="img" class="imaged w-100">';
                    }else{
                        $arrGambar = explode(';', $list_terlaris[$i]->gambar);
                        if ($list_terlaris[$i]->source == 'POS') {
                            $tmp .= '<img src="'.env('ADMIN_URL').'asset/foto_produk/'.$arrGambar[0].'" style="width=83.66px; height:83.66px"  alt="img" class="imaged w-100">';
                        } else {
                            $tmp .= '<img src="'.env('ADMIN_URL').'asset/foto_produk/'.$arrGambar[0].'" style="width=83.66px; height:83.66px"  alt="img" class="imaged w-100">';
                        }
                    }

                    if(strlen($list_terlaris[$i]->nama_produk) > 30) {
                        $nama_barang = substr($list_terlaris[$i]->nama_produk, 0, 30) . '...';
                    }else{
                        $nama_barang = $list_terlaris[$i]->nama_produk;
                    }
                                        
                    $tmp .= '<strong>'.$nama_barang.'</strong>
                    </div>
                </a>
            </div>';

        }

        return $tmp;
    }

    public function ScanBarcode(Request $req)
    {
        $dtBarang = Session::get('list_barang');
        $indexNya = array_search($req->barcode, array_column($dtBarang, 'sku'));
        if ($indexNya !== false && $indexNya >= 0  ){
            return $dtBarang[$indexNya]->id_produk;
        } else {
            return false;
        }
    }

    public function AddKeranjang(Request $req)
    {
        $dataPos = Session::get('POS');
        if($dataPos['id_konsumen'] <= 0){
            http_response_code(404);
            exit(json_encode(['Message' => 'Konsumen Belum Diinput']));
            return;
        }
        $now = date('Y-m-d');
        $resellerPaket = DB::table('rb_reseller_paket as a')
                    ->select('a.*', 'b.nama_paket', 'b.durasi', 'b.lable_harga')
                    ->join('rb_paket as b', 'a.id_paket', '=', 'b.id_paket')
                    ->where('a.id_reseller', '=', '423')
                    ->where('a.users', '=', 'konsumen')
                    ->where('a.status', '=', 'Y')
                    ->where('a.expire_date', '>=', $now)
                    ->first();
        if($resellerPaket){
            $paket = $resellerPaket->lable_harga;
        } else {
            $paket = 'harga_konsumen';
        }
        
        
        App::setLocale(session()->get('locale'));

        $qtyBeli = 1;

        $id_barang = $req->id_barang;

        // echo $id_barang.' || ';
        // return array_column(Session::get('list_barang'), 'id_barang');
        $dtKeranjang = Session::get('keranjang');
        $dtBarang = Session::get('list_barang');
        $dtVariasi = Session::get('list_variasi');
        $indexNya = array_search($id_barang, array_column($dtBarang, 'id_produk'));
        $indexKeranjang = array_search($id_barang, array_column($dtKeranjang, 'id_produk'));
        
        $arrVariasi = array_values(array_filter($dtVariasi, function ($var) use ($id_barang) {
            return ($var->id_produk == $id_barang);
        }));

        // $indexVariasi = array_search($req->id_barang, array_column($dtVariasi, 'id_produk'));
        // echo $dtBarang[$indexNya]->nama_produk;
        // echo json_encode($arrVariasi);return;
        if ($indexNya >= 0) {
            if ($indexKeranjang !== false && $indexKeranjang >= 0  ) {
                $qtyTemp = $dtKeranjang[$indexKeranjang]['qty'] + $qtyBeli;

                if ($qtyTemp >= $dtBarang[$indexNya]->harga_konsumen_minimal_order) {
                    $paket = 'harga_konsumen';
                }

                if ($qtyTemp >= $dtBarang[$indexNya]->harga_reseller_minimal_order) {
                    $paket = 'harga_reseller';
                }
            }

            $nama_produk = $dtBarang[$indexNya]->nama_produk;
            $harga_konsumen = $dtBarang[$indexNya]->$paket; 
            $harga_premium = $dtBarang[$indexNya]->harga_premium;
            $harga_reseller = $dtBarang[$indexNya]->harga_reseller;

            $arrMasukKeranjang['variasi'] = [];
            if (count($arrVariasi) > 0) {
                for ($i=0; $i < count($arrVariasi); $i++) { 
                    $dtVariasi = explode(';', $arrVariasi[$i]->variasi);
                    $dtHarga = explode(';', $arrVariasi[$i]->$paket);
                    $nama_produk = $nama_produk.', '.$arrVariasi[$i]->nama.': '.$dtVariasi[0];
                    $hargaTambah = $dtHarga[0];
                    $harga_konsumen = $harga_konsumen + $hargaTambah;
                    $harga_premium = $harga_premium + $hargaTambah;
                    $harga_reseller = $harga_reseller + $hargaTambah;
                    $arrMasukKeranjang['variasi'][$i] = $arrVariasi[$i]->nama.';'.$dtVariasi[0].';'.$dtHarga[0];
                }
            }

            $arrMasukKeranjang['id_produk'] = $dtBarang[$indexNya]->id_produk;
            $arrMasukKeranjang['nama_produk'] = $nama_produk;
            $arrMasukKeranjang['qty'] = $qtyBeli;
            $arrMasukKeranjang['harga_beli'] = $dtBarang[$indexNya]->harga_beli;
            $arrMasukKeranjang['harga_konsumen'] = $harga_konsumen;
            $arrMasukKeranjang['harga_premium'] = $harga_premium;
            $arrMasukKeranjang['harga_reseller'] = $harga_reseller;
            $arrMasukKeranjang['diskon'] = $dtBarang[$indexNya]->diskon;
            $arrMasukKeranjang['satuan'] = $dtBarang[$indexNya]->satuan;
            $arrMasukKeranjang['berat'] = $dtBarang[$indexNya]->berat;
            

            if ($indexKeranjang !== false && $indexKeranjang >= 0  ) {
                $dtKeranjang[$indexKeranjang]['qty'] = $dtKeranjang[$indexKeranjang]['qty'] + $qtyBeli;
                $dtKeranjang[$indexKeranjang]['harga_konsumen'] = $harga_konsumen;
            } else {
                array_push($dtKeranjang, $arrMasukKeranjang);
            }
            // dd($arrMasukKeranjang);
            Session::put('keranjang', $dtKeranjang);
            http_response_code(200);
            return json_encode($indexNya);
        }

        http_response_code(404);
        exit(json_encode(['Message' => __('bahasa.notif_barang_tidak_ditemukan')]));
        return;
        
        
    }

    public function ViewKeranjang(Request $req)
    {
        App::setLocale(session()->get('locale'));

        $dataPos = [];
        if (Session::has('POS')) {
            $dataPos = Session::get('POS');
        } else {
            $dataPos['pengiriman']['ongkir'] = 0;
            $dataPos['pengiriman']['kurir'] = 'tanpa_ongkir';
            $dataPos['diskon'] = 0;
            $dataPos['bayar'] = 0;
            $dataPos['tipe_konsumen'] = 'umum';
            $dataPos['no_konsumen'] = 0;
            $dataPos['nama_konsumen'] = '';
            $dataPos['id_konsumen'] = 0;

            $dataPos['voucher']['id_kupon'] = '';
            $dataPos['voucher']['kode_kupon'] = '';
            $dataPos['voucher']['id_produk_kupon'] = '';
            $dataPos['voucher']['min_order'] = '';
            $dataPos['voucher']['keterangan'] = '';
            $dataPos['voucher']['catatan'] = '';
        }
        // return json_encode($dataPos);
        Session::put('POS', $dataPos);

        $data['POS'] = $dataPos;
        
        if(!Session::has('keranjang')) {
            Session::put('keranjang', []);
        }

        $data['dtKeranjang'] =  Session::get('keranjang');
        return view('pos.keranjang_list', $data);
    }


    public function PilihPengiriman(Request $req)
    {
        App::setLocale(session()->get('locale'));

        $dataPos = Session::get('POS');
        // return json_encode($dataPos);

        if ($req->pengiriman == 'tanpa_ongkir') {
            $dataPos['pengiriman']['ongkir'] = 0;
            $dataPos['pengiriman']['kurir'] = 'tanpa_ongkir';
        } else if ($req->pengiriman == 'ongkir_toko') {
            $dataPos['pengiriman']['ongkir'] = 7000;
            $dataPos['pengiriman']['kurir'] = 'ongkir_toko';
        } else if ($req->pengiriman == 'ongkir_lokal') {
            $id_konsumen = $dataPos['id_konsumen'];
            if($id_konsumen == '0'){
                http_response_code(404);
                exit(json_encode(['Konsumen Belum Ada Terdaftar']));
            }
            
            $cek_koordinat_konsumen = DB::table('rb_konsumen')->where('id_konsumen', $id_konsumen)->first();
            // if ($cek_koordinat_konsumen->kordinat_lokasi == '') {
            //     http_response_code(404);
            //     exit(json_encode(['Konsumen Belum Ada Koordinat']));
            // }

            $token_penjual = Session::get('token');
            $cekPenjualKonsumen = DB::table('rb_konsumen')->where('remember_token', $token_penjual)->first();
            $dtPenjual = DB::table('rb_reseller')->where('id_konsumen', $cekPenjualKonsumen->id_konsumen)->first();
            $cek_koordinat_penjual = DB::table('rb_reseller')->where('id_reseller', $dtPenjual->id_reseller)->first();
            if ($cek_koordinat_penjual->kordinat == '') {
                http_response_code(404);
                exit(json_encode([__('bahasa.notif_penjual_belum_ada_koordinat')]));
            }

            // $response = Http::withHeaders([ 
            //     'Accept'=> '*/*', 
            // ]) 
            // ->get('https://api.satutoko.id/api/kurir/v1/hitungJarak/'.trim($cek_koordinat_penjual->kordinat,' ').'/'.trim($cek_koordinat_konsumen->kordinat_lokasi, ' ').'/REGULAR');
            
            // if ($response->body() < 0) {
            //     http_response_code(404);
            //     exit(json_encode(__('bahasa.notif_jarak_terlalu_jauh')));
            // }

            // $dataPos['pengiriman']['ongkir'] = $response->body();
            $dataPos['pengiriman']['ongkir'] = 7000;
            $dataPos['pengiriman']['kurir'] = 'ongkir_lokal';
            $dataPos['pengiriman']['titik_jemput'] = trim($cek_koordinat_penjual->kordinat,' ');
            $dataPos['pengiriman']['titik_antar'] = trim($cek_koordinat_konsumen->kordinat_lokasi,' ');
        } else {
            $dataPos['pengiriman']['ongkir'] = 0;
            $dataPos['pengiriman']['kurir'] = $req->pengiriman;
        }

        Session::put('POS', $dataPos);
    }

    public function InputDiskon(Request $req)
    {
        $dataPos = Session::get('POS');

        $dataPos['diskon'] = $req->amount;

        Session::put('POS', $dataPos);
    }

    public function InputBayar(Request $req)
    {
        $dataPos = Session::get('POS');

        $dataPos['bayar'] = $req->bayar;

        Session::put('POS', $dataPos);
    }


    public function EditBarangKeranjang(Request $req)
    {
        $dataPos = Session::get('POS');
        if($dataPos['id_konsumen'] <= 0){
            http_response_code(404);
            exit(json_encode(['Message' => 'Konsumen Belum Diinput']));
            return;
        }
        $now = date('Y-m-d');
        $resellerPaket = DB::table('rb_reseller_paket as a')
                    ->select('a.*', 'b.nama_paket', 'b.durasi', 'b.lable_harga')
                    ->join('rb_paket as b', 'a.id_paket', '=', 'b.id_paket')
                    ->where('a.id_reseller', '=', '423')
                    ->where('a.users', '=', 'konsumen')
                    ->where('a.status', '=', 'Y')
                    ->where('a.expire_date', '>=', $now)
                    ->first();
        if($resellerPaket){
            $paket = $resellerPaket->lable_harga;
        } else {
            $paket = 'harga_level_newbie';
        }
        
        App::setLocale(session()->get('locale'));

        $tmp = '';
        $id_barang = $req->id_barang;

        $dtKeranjang = Session::get('keranjang');
        $dtBarang = Session::get('list_barang');
        $dtVariasi = Session::get('list_variasi');
        
        $indexNya = array_search($id_barang, array_column($dtBarang, 'id_produk'));
        $indexKeranjang = array_search($id_barang, array_column($dtKeranjang, 'id_produk'));
        
        $arrVariasi = array_values(array_filter($dtVariasi, function ($var) use ($id_barang) {
            return ($var->id_produk == $id_barang);
        }));

        // barang
        $arrGambar = explode(';', $dtBarang[$indexNya]->gambar);
        $tmp .= '
        <div class="text-center">
            <img src="https://satutoko.id/asset/foto_produk/'.$arrGambar[0].'" alt="image" class="imaged w100 mb-1">
            <br/><span>'.$dtBarang[$indexNya]->nama_produk.'</span>
            <br/><span>'.__('bahsa.kurs').number_format($dtBarang[$indexNya]->harga_konsumen).'</span>
        </div>';

        $tmp .= '
        <div class="form-group basic">
            <div class="input-wrapper">
                <label class="label" for="account1">Qty</label>
                <input type="number" class="form-control" name="edit_qty" value="'.$dtKeranjang[$indexKeranjang]['qty'].'">
                <input type="hidden" class="form-control" name="edit_id" value="'.$id_barang.'">
                <div class="input-info text-danger">'.__('bahasa.masukan_0_untuk_menghapus').'</div>
            </div>
        </div>
        ';

        for ($i=0; $i < count($arrVariasi); $i++) { 
            $detailVariasi = explode(';',$arrVariasi[$i]->variasi);
            $hargaVariasi = explode(';',$arrVariasi[$i]->$paket);
            $tmp .= '
            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="account1">'.$arrVariasi[$i]->nama.'</label>
                    <select class="form-control" name="edit_variasi[]" >';
                        for ($j=0; $j < count($detailVariasi); $j++) { 
                            if (array_key_exists($j, $hargaVariasi)) {
                                $hargaPlus = $hargaVariasi[$j];
                            } else {
                                $hargaPlus = 0;
                            }

                            $selected = '';
                            if (stripos(json_encode($dtKeranjang[$indexKeranjang]['variasi']), $arrVariasi[$i]->nama.';'.$detailVariasi[$j].';'.$hargaPlus ) !== false) {
                                $selected = 'selected';
                            }
                            $tmp .= '<option '.$selected.' value="'.$arrVariasi[$i]->nama.';'.$detailVariasi[$j].';'.$hargaPlus.'">'.$detailVariasi[$j].' (+'.number_format($hargaPlus).')</option>';
                        }
                    $tmp .='
                    </select>
                </div>
            </div>
            ';
        }


        return $tmp;
    }

    public function UpdateBarangKeranjang(Request $req)
    {
        $id_barang = $req->edit_id;
        $qty = $req->edit_qty;
        $variasi = $req->edit_variasi;

        if(!is_array($variasi)){
            $variasi = [];
        }

        
        $dtKeranjang = Session::get('keranjang');
        $dtBarang = Session::get('list_barang');
        
        $indexNya = array_search($id_barang, array_column($dtBarang, 'id_produk'));
        $indexKeranjang = array_search($id_barang, array_column($dtKeranjang, 'id_produk'));
        
        if ($qty <= 0) {
            unset($dtKeranjang[$indexKeranjang]); // remove item at index 0
            $dtKeranjang = array_values($dtKeranjang); // 'reindex' array
        } else {

            $nama_produk = $dtBarang[$indexNya]->nama_produk;
            $harga_konsumen = $dtBarang[$indexNya]->harga_konsumen;
            $harga_premium = $dtBarang[$indexNya]->harga_premium;
            $harga_reseller = $dtBarang[$indexNya]->harga_reseller;

            $dtKeranjang[$indexKeranjang]['variasi'] = [];
            for ($i=0; $i < count($variasi); $i++){
                $arrVariasi = explode(';', $variasi[$i]);
                $nama_produk = $nama_produk.', '.$arrVariasi[0].': '.$arrVariasi[1];
                $hargaTambah = $arrVariasi[2];
                $harga_konsumen = $harga_konsumen + $hargaTambah;
                $harga_premium = $harga_premium + $hargaTambah;
                $harga_reseller = $harga_reseller + $hargaTambah;
                $dtKeranjang[$indexKeranjang]['variasi'][$i] = $arrVariasi[0].';'.$arrVariasi[1].';'.$arrVariasi[2];
            }

            $dtKeranjang[$indexKeranjang]['nama_produk'] = $nama_produk;
            $dtKeranjang[$indexKeranjang]['qty'] = $qty;
            $dtKeranjang[$indexKeranjang]['harga_konsumen'] = $harga_konsumen;
            $dtKeranjang[$indexKeranjang]['harga_premium'] = $harga_premium;
            $dtKeranjang[$indexKeranjang]['harga_reseller'] = $harga_reseller;
        
        }
        Session::put('keranjang', $dtKeranjang);
        return 'OK';
    }


    public function CekKonsumen(Request $req)
    {
        $no_hp = $req->no_hp;
        $cek_konsumen = DB::table('rb_konsumen')->where('no_hp', $no_hp)->first();

        $dataPos = Session::get('POS');
        $dataPos['no_konsumen'] = $no_hp;

        if ($cek_konsumen) {
            $dataPos['tipe_konsumen'] = 'marketplace';
            $dataPos['id_konsumen'] = $cek_konsumen->id_konsumen;
            $nama = $cek_konsumen->nama_lengkap;
            $alamat = $cek_konsumen->alamat_lengkap ?? '';
        } else {
            $dataPos['tipe_konsumen'] = 'umum';
            $dataPos['id_konsumen'] = 0;
            $nama = 'Konsumen Umum';
            $alamat = '';
        }

        $dataPos['nama_konsumen'] = $nama;
        $dataPos['pengiriman']['alamat_antar'] = $alamat;
        Session::put('POS', $dataPos);

        return response()->json(['nama' => $nama, 'alamat' => $alamat]);
    }


    public function CekKupon(Request $req)
    {
        App::setLocale(session()->get('locale'));

        $kupon = $req->kode_voucher;
        if ($kupon == '' || $kupon == '0') {
            http_response_code(400);
            exit(json_encode([__('bahasa.notif_kupon_tidak_memenuhi_syarat')]));
        }

        $response = Http::attach('token',Session::get('token'))
        ->attach('kode_kupon',$kupon)
        ->withHeaders([ 
            'Authorization'=> api_token(),
        ]) 
        ->post(api_url().'api/v1/cek_kupon'); 

        $cek_kupon = json_decode($response->body());
        

        $bisa_kupon = true;

        if($response->getStatusCode() == '200'){

            $dtKeranjang = Session::get('keranjang');
            if ($cek_kupon->id_produk > 0) {
                if (count($dtKeranjang) == 0) {
                    $bisa_kupon = false;
                    // http_response_code(400);
                    // exit(json_encode(['Isi Barang Terlebih Dahulu']));
                } else {
                    $indexKeranjang = array_search($cek_kupon->id_produk, array_column($dtKeranjang, 'id_produk'));
                    if (!$indexKeranjang) {
                        $bisa_kupon = false;
                        // http_response_code(400);
                        // exit(json_encode(['Kupon Tidak Memenuhi Syarat']));
                    } else {
                        if ($dtKeranjang[$indexKeranjang]['qty'] < $cek_kupon->min_order) {
                            $bisa_kupon = false;
                            // http_response_code(400);
                            // exit(json_encode(['Kupon Tidak Memenuhi Syarat']));
                        }
                    }
                }
                
            } else {
                $total_belanja = 0;
                foreach ($dtKeranjang as $row) {
                    $total_belanja = $total_belanja + ($row['qty'] * $row['harga_konsumen']);
                }

                if ($total_belanja < $cek_kupon->min_order) {
                    $bisa_kupon = false;
                    // http_response_code(400);
                    // exit(json_encode(['Kupon Tidak Memenuhi Syarat']));
                }
            }

        } else {
            $bisa_kupon = false;
        }
        

        $dtPos = Session::get('POS');

        if ($bisa_kupon) {
            $dtPos['diskon'] = $cek_kupon->nilai_kupon;
            $dtPos['voucher']['id_kupon'] = $cek_kupon->id_kupon;
            $dtPos['voucher']['kode_kupon'] = $cek_kupon->kode_kupon;
            $dtPos['voucher']['id_produk_kupon'] = $cek_kupon->id_produk;
            $dtPos['voucher']['min_order'] = $cek_kupon->min_order;
            $dtPos['voucher']['keterangan'] = $cek_kupon->keterangan;
            $dtPos['voucher']['catatan'] = $cek_kupon->catatan;

            Session::put('POS', $dtPos);
            return json_encode(Session::get('POS'));
            http_response_code(200);
            exit(json_encode([__('bahasa.notif_kupon_berhasil_ditambahkan')]));
        } else {
            $dtPos['diskon'] = 0;
            $dtPos['voucher']['id_kupon'] = '';
            $dtPos['voucher']['kode_kupon'] = '';
            $dtPos['voucher']['id_produk_kupon'] = '';
            $dtPos['voucher']['min_order'] = '';
            $dtPos['voucher']['keterangan'] = '';
            $dtPos['voucher']['catatan'] = '';

            Session::put('POS', $dtPos);
            http_response_code(400);
            exit(json_encode([__('bahasa.notif_kupon_tidak_memenuhi_syarat')]));
        }
        
        
        
    }



    public function PosBayar(Request $req)
    {
        App::setLocale(session()->get('locale'));

        $dtKeranjang = Session::get('keranjang');
        $dtPos = Session::get('POS');
        // dd($dtPos);

        $subTotal = 0;
        for ($i=0; $i < count($dtKeranjang); $i++) { 
            $subTotal = $subTotal + ($dtKeranjang[$i]['harga_konsumen'] * $dtKeranjang[$i]['qty']);
        }
        $totalBelanja = $subTotal + $dtPos['pengiriman']['ongkir'] - $dtPos['diskon'];

        $alamat_pengiriman = $req->alamat_pengiriman ?? ($dtPos['pengiriman']['alamat_antar'] ?? '');

        $insertMaster['kode_transaksi'] = 'POS-'.time();
        $insertMaster['id_pembeli'] = $dtPos['id_konsumen'];
        $insertMaster['id_penjual'] = $this->getDataToko()->id_reseller;
        $insertMaster['status_pembeli'] = 'konsumen';
        $insertMaster['status_penjual'] = 'reseller';
        $insertMaster['kurir'] = $dtPos['pengiriman']['kurir'];
        $insertMaster['ongkir'] = $dtPos['pengiriman']['ongkir'];
        $insertMaster['service'] = '-';
        $insertMaster['alamat_pengiriman'] = $alamat_pengiriman;
        $insertMaster['waktu_transaksi'] = date('Y-m-d H:i:s');

        if ($dtPos['pengiriman']['kurir'] == 'tanpa_ongkir') {
            // $insertMaster['proses'] = '4';
        }else {
            $insertMaster['proses'] = '1';
        } 

        // CREDIT
        if (($dtPos['bayar'] - $totalBelanja) < 0) {
            if ($dtPos['tipe_konsumen'] == 'umum') {
                http_response_code(400);
                exit(json_encode(__('bahasa.notif_konsumen_umum_tidak_bisa_kredit')));
            }
            
        }

        try {
            DB::beginTransaction();
            $id = DB::table('rb_penjualan')->insertGetId($insertMaster);

            for ($k=0; $k < count($dtKeranjang); $k++) { 
                $insertDetail['id_penjualan'] = $id;
                $insertDetail['id_produk'] = $dtKeranjang[$k]['id_produk'];
                $insertDetail['jumlah'] = $dtKeranjang[$k]['qty'];
                $insertDetail['diskon'] = $dtKeranjang[$k]['diskon'];
                $insertDetail['harga_jual'] = $dtKeranjang[$k]['harga_konsumen'];
                $insertDetail['satuan'] = $dtKeranjang[$k]['satuan'];
                $cekinsert = DB::table('rb_penjualan_detail')->insert($insertDetail);
            }
            
            if ($dtPos['pengiriman']['kurir'] == 'ongkir_lokal') {
                $this->orderKurir(
                    $dtPos['pengiriman']['titik_jemput'],
                    $dtPos['pengiriman']['titik_antar'],
                    'REGULAR',
                    $id,
                    'LIVE_ORDER',
                    $alamat_pengiriman,
                    $dtPos['id_konsumen'],
                    $this->getDataToko()->id_reseller
                );
            }

            DB::commit();
            // DB::rollback();

            // return $cekinsert;
            exit(json_encode(['Message' => 'Transaksi Sukses']));
        } catch (\Exception $e) {
            DB::rollback();
            http_response_code(500);
            exit(json_encode(['Message' => 'Transaksi Gagal: '.$e->getMessage()]));
        }
        
    }

    public function PosFinish(Request $req)
    {
        Session::forget('keranjang');

        $dataPos = [];
            $dataPos['pengiriman']['ongkir'] = 0;
            $dataPos['pengiriman']['kurir'] = 'tanpa_ongkir';
            $dataPos['diskon'] = 0;
            $dataPos['bayar'] = 0;
            $dataPos['tipe_konsumen'] = 'umum';
            $dataPos['no_konsumen'] = 0;
            $dataPos['nama_konsumen'] = '';
            $dataPos['id_konsumen'] = 0;

            $dataPos['voucher']['id_kupon'] = '';
            $dataPos['voucher']['kode_kupon'] = '';
            $dataPos['voucher']['id_produk_kupon'] = '';
            $dataPos['voucher']['min_order'] = '';
            $dataPos['voucher']['keterangan'] = '';
            $dataPos['voucher']['catatan'] = '';
        
        Session::put('POS', $dataPos);

        return redirect('/pos');
    }



    public function orderKurir($titik_jemput, $titik_tujuan, $service, $id_penjualan, $source, $alamat_antar = '', $id_pemesan = 0, $id_reseller = 0)
    {
        if ($titik_jemput == '') {
            throw new \Exception('Koordinat alamat jemput (toko) belum diatur');
        }

        if ($alamat_antar == '') {
            throw new \Exception('Alamat tujuan (konsumen) belum diisi');
        }

        $tarif = 7000;

        $data['tarif'] = $tarif;

        if ($id_penjualan != '' && ($source == 'POS' || $source == 'MP')) {
            $cekPenjualan = DB::table('kurir_order')
                ->where('id_penjualan', $id_penjualan)
                ->where('source', $source)
                ->whereNotIn('status', ['cancel', 'onprocess'])
                ->get();
            if (count($cekPenjualan) > 0) {
                throw new \Exception('Id Penjualan Masih Aktif');
            }

            

            $data['kode_order'] = 'SND-' . time();
        }

        $data['id_penjualan'] = $id_penjualan;
        $data['id_pemesan'] = $id_pemesan;
        $data['id_reseller'] = $id_reseller;
        $data['metode_pembayaran'] = 'WALLET';
        $data['titik_jemput'] = $titik_jemput;
        $data['titik_antar'] = $titik_tujuan;
        $data['alamat_antar'] = $alamat_antar;
        $data['service'] = $service;
        $data['tanggal_order'] = date('Y-m-d H:i:s');
        $data['source'] = $source;
        $data['status'] = 'SEARCH';

        $insertOrder = DB::table('kurir_order')->insert($data);
        $id = DB::getPdo()->lastInsertId();

        if (!$insertOrder) {
            throw new \Exception('Kesalahan Menyimpan Order Kurir');
        }

        Http::withHeaders(['Accept' => '*/*'])
            ->get(api_url().'api/notification/new_order/'.$id_penjualan);

        return $id;
    }
}
