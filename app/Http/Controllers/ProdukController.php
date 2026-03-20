<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App;
use DB;
use Illuminate\Support\Facades\Http;

class ProdukController extends Controller
{
    public function index(Request $req)
    {
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }
        
        $data['title'] = 'Produk';
        $data['header'] = 'goback';
        $data['menu'] = '';

        $data['list_kategori'] = $this->getKategori();

        return view('produk.produk',$data);
    }

    public function searchProduk(Request $req)
    {
        $kategori = $req->kategori;

        $dt = DB::table('rb_produk as a')
        ->select('a.*', 'b.nama_kategori')
        ->leftJoin('rb_kategori_produk as b', 'b.id_kategori_produk', 'a.id_kategori_produk');
        if ($kategori) {
            $dt = $dt->where('a.id_kategori_produk', $kategori);
        }
        $dt = $dt->where('a.id_reseller', $this->getDataToko()->id_reseller)->orderBy('a.nama_produk')->get();
        return $dt;
    }

    public function addProduk(Request $req)
    {
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }
        
        $data['title'] = 'Add Produk';
        $data['header'] = 'goback';
        $data['menu'] = '';

        $data['list_kategori'] = $this->getKategori();

        return view('produk.add_produk',$data);
    }

    public function getKategori($id='')
    {
        $dt = DB::table('rb_kategori_produk');

        if ($id) {
            $dt = $dt->where('id_kategori_produk', $id);
        }

        $dt = $dt->orderBy('nama_kategori')->get();
        return $dt;
    }

    public function getSubKategoriProduk(Request $req)
    {
        $dt = DB::table('rb_kategori_produk_sub');

        if ($req->kategori) {
            $dt = $dt->where('id_kategori_produk_sub', $req->kategori);
        }

        $dt = $dt->orderBy('nama_kategori_sub')->get();
        return $dt;
    }


    public function saveProduk(Request $req)
    {
        
        if ($req->nama_barang == '') {
            http_response_code(400);
            exit(json_encode(['Message' => 'Nama Barang Harus Diisi']));
        }
        if ($req->harga_beli == '') {
            http_response_code(400);
            exit(json_encode(['Message' => 'Harga Beli Harus Diisi']));
        }
        if ($req->harga_jual == '') {
            http_response_code(400);
            exit(json_encode(['Message' => 'Harga Jual Harus Diisi']));
        }
        if ($req->deskripsi_produk == '') {
            http_response_code(400);
            exit(json_encode(['Message' => 'Deskripsi Barang Harus Diisi']));
        }
        if ($req->harga_reseller > 0) {
            if ($req->harga_konsumen_minimal_order <= 0) {
                http_response_code(400);
                exit(json_encode(['Message' => 'Minimal Order Ecer Harus Diisi']));
            }
            if ($req->harga_reseller_minimal_order <= 0) {
                http_response_code(400);
                exit(json_encode(['Message' => 'Minimal Order Reseller Harus Diisi']));
            }
        }

        $allowed_ext= array('image/jpg','image/jpeg','image/png','image/gif');

        $foto1  = $_FILES['fileuploadInput1'];
        $foto2  = $_FILES['fileuploadInput2'];
        $foto3  = $_FILES['fileuploadInput3'];
        $foto4  = $_FILES['fileuploadInput4'];

        $dtFoto = '';


        if ($foto1['size'] > 0) {
            $foto = $foto1;
            $file_name =$foto['name'];
            $file_ext = $foto['type'];
            $file_size=$foto['size'];
            $file_tmp= $foto['tmp_name'];
            $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
            $data = file_get_contents($file_tmp);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            
            if(in_array($file_ext,$allowed_ext) === false)
            {
                http_response_code(400);
                exit(json_encode(['Message' => 'Type File Foto 1 Tidak Diijinkan']));
            }
            if($file_size > 1100000)
            {
                http_response_code(400);
                exit(json_encode(['Message' => 'Ukuran Foto 1 Maksimal 1mb']));
            }
            
            $imageName = time().'.'.$req->fileuploadInput1->extension();
            $req->fileuploadInput1->move('/var/www/dpb-admin/public/asset/foto_produk', $imageName);

            if ($dtFoto != '') {
                $dtFoto .= $dtFoto.';'.$imageName;
            }else{
                $dtFoto = $imageName;
            }
        }

        if ($foto2['size'] > 0) {
            $foto = $foto2;
            $file_name =$foto['name'];
            $file_ext = $foto['type'];
            $file_size=$foto['size'];
            $file_tmp= $foto['tmp_name'];
            $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
            $data = file_get_contents($file_tmp);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            
            if(in_array($file_ext,$allowed_ext) === false)
            {
                http_response_code(400);
                exit(json_encode(['Message' => 'Type File Foto 2 Tidak Diijinkan']));
            }
            if($file_size > 1100000)
            {
                http_response_code(400);
                exit(json_encode(['Message' => 'Ukuran Foto 2 Maksimal 1mb']));
            }

            $imageName = time().'.'.$req->fileuploadInput2->extension();
            $req->fileuploadInput2->move('/var/www/dpb-admin/public/asset/foto_produk', $imageName);

            if ($dtFoto != '') {
                $dtFoto .= $dtFoto.';'.$imageName;
            }else{
                $dtFoto = $imageName;
            }
        }

        if ($foto3['size'] > 0) {
            $foto = $foto3;
            $file_name =$foto['name'];
            $file_ext = $foto['type'];
            $file_size=$foto['size'];
            $file_tmp= $foto['tmp_name'];
            $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
            $data = file_get_contents($file_tmp);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            
            if(in_array($file_ext,$allowed_ext) === false)
            {
                http_response_code(400);
                exit(json_encode(['Message' => 'Type File Foto 3 Tidak Diijinkan']));
            }
            if($file_size > 1100000)
            {
                http_response_code(400);
                exit(json_encode(['Message' => 'Ukuran Foto 3 Maksimal 1mb']));
            }

            $imageName = time().'.'.$req->fileuploadInput3->extension();
            $req->fileuploadInput3->move('/var/www/dpb-admin/public/asset/foto_produk', $imageName);

            if ($dtFoto != '') {
                $dtFoto .= $dtFoto.';'.$imageName;
            }else{
                $dtFoto = $imageName;
            }
        }

        if ($foto4['size'] > 0) {
            $foto = $foto4;
            $file_name =$foto['name'];
            $file_ext = $foto['type'];
            $file_size=$foto['size'];
            $file_tmp= $foto['tmp_name'];
            $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
            $data = file_get_contents($file_tmp);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            
            if(in_array($file_ext,$allowed_ext) === false)
            {
                http_response_code(400);
                exit(json_encode(['Message' => 'Type File Foto 4 Tidak Diijinkan']));
            }
            if($file_size > 1100000)
            {
                http_response_code(400);
                exit(json_encode(['Message' => 'Ukuran Foto 4 Maksimal 1mb']));
            }

            $imageName = time().'.'.$req->fileuploadInput4->extension();
            $req->fileuploadInput4->move('/var/www/dpb-admin/public/asset/foto_produk', $imageName);

            if ($dtFoto != '') {
                $dtFoto .= $dtFoto.';'.$imageName;
            }else{
                $dtFoto = $imageName;
            }
        }

        $dtHeadInsert['id_produk_perusahaan'] = '0';
        $dtHeadInsert['id_kategori_produk'] = $req->kategori_produk;
        $dtHeadInsert['id_kategori_produk_sub'] = $req->sub_kategori_produk;
        $dtHeadInsert['id_reseller'] = $this->getDataToko()->id_reseller;
        $dtHeadInsert['nama_produk'] = $req->nama_barang;
        $dtHeadInsert['produk_seo'] = str_replace(" ", "_", $req->nama_barang);
        $dtHeadInsert['satuan'] = $req->satuan;
        $dtHeadInsert['harga_beli'] = $req->harga_beli;
        $dtHeadInsert['harga_reseller'] = ($req->harga_reseller >= 0)?$req->harga_reseller:0;
        $dtHeadInsert['harga_konsumen'] = ($req->harga_jual >= 0)?$req->harga_jual:0;
        $dtHeadInsert['harga_premium'] = (isset($req->harga_premium) && $req->harga_premium >= 0)?$req->harga_premium:0;
        $dtHeadInsert['harga_platform'] = ($req->harga_platform >= 0)?$req->harga_jual-($req->harga_jual-(($req->harga_jual*$req->harga_platform)/100)):0;
        $dtHeadInsert['harga_platform_persen'] = ($req->harga_platform >= 0)?$req->harga_platform:0;
        $dtHeadInsert['harga_konsumen_minimal_order'] = ($req->harga_konsumen_minimal_order >= 0)?$req->harga_konsumen_minimal_order:0;
        $dtHeadInsert['harga_reseller_minimal_order'] = ($req->harga_reseller_minimal_order >= 0)?$req->harga_reseller_minimal_order:0;
        // $dtHeadInsert['harga_level_newbie'] = ($req->harga_level_newbie >= 0)?$req->harga_level_newbie:0;
        // $dtHeadInsert['harga_level_pedagang'] = ($req->harga_level_pedagang >= 0)?$req->harga_level_pedagang:0;
        // $dtHeadInsert['harga_level_juragan'] = ($req->harga_level_juragan >= 0)?$req->harga_level_juragan:0;
        // $dtHeadInsert['harga_level_big'] = ($req->harga_level_big >= 0)?$req->harga_level_big:0;
        // $dtHeadInsert['harga_level_bos'] = ($req->harga_level_bos >= 0)?$req->harga_level_bos:0;
        $dtHeadInsert['berat'] = $req->berat;
        $dtHeadInsert['username'] = $this->getDataToko()->id_konsumen;
        $dtHeadInsert['aktif'] = 'Y';
        $dtHeadInsert['tag'] = '';
        $dtHeadInsert['minimum'] = '1';
        $dtHeadInsert['sku'] = $req->barcode;
        $dtHeadInsert['fee_produk'] = '0';
        $dtHeadInsert['jenis_produk'] = 'Fisik';
        $dtHeadInsert['waktu_input'] = date('Y-m-d H:i:s');
        $dtHeadInsert['source'] = 'POS';
        $dtHeadInsert['gambar'] = $dtFoto;
        $dtHeadInsert['keterangan'] = '-';
        $dtHeadInsert['tentang_produk'] = $req->deskripsi_produk;


        if ($req->type_variasi) {
            if (count($req->type_variasi) != count($req->penambah_harga_variasi) && count($req->type_variasi) != count($req->variasi)) {
                http_response_code(400);
                exit(json_encode(['Message' => 'Ada Kesalahan di Pengisian Variasi']));
            }
            
            if (in_array(null, $req->type_variasi) && in_array(null, $req->penambah_harga_variasi) && in_array(null, $req->variasi) ) {
                http_response_code(400);
                exit(json_encode(['Message' => 'Ada Kesalahan di Pengisian Variasi']));
            }
            
            $id_produk = DB::table('rb_produk')->insertGetId($dtHeadInsert);

            for ($i=0; $i < count($req->type_variasi); $i++) { 
                $dtVariasi['id_produk'] = $id_produk;
                $dtVariasi['nama'] = $req->type_variasi[$i];
                $dtVariasi['variasi'] = $req->variasi[$i];
                $dtVariasi['variasi_harga'] = $req->penambah_harga_variasi[$i];

                DB::table('rb_produk_variasi')->insert($dtVariasi);
            }
            
        } else {
            DB::table('rb_produk')->insertGetId($dtHeadInsert);
        }

        http_response_code(200);
        exit(json_encode(['Message' => 'Berhasil Menyimpan Barang']));
    }

    public function editProduk($id)
    {
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }

        $dt = DB::table('rb_produk as a')
        ->select('a.*', 'b.nama_kategori')
        ->leftJoin('rb_kategori_produk as b', 'b.id_kategori_produk', 'a.id_kategori_produk')
        // ->where('a.id_reseller', $this->getDataToko()->id_reseller)
        ->where('a.id_produk', $id)
        ->orderBy('a.nama_produk')->first();

        $dtVariasi = DB::table('rb_produk_variasi')->where('id_produk', $id)->get();
        
        $data['title'] = 'Edit Produk';
        $data['header'] = 'goback';
        $data['menu'] = '';

        $data['dt_produk'] = $dt;
        $data['dt_produk_varias'] = ($dtVariasi);
        $data['list_kategori'] = $this->getKategori();
        $data['list_kategori_sub'] = DB::table('rb_kategori_produk_sub')->where('id_kategori_produk', $dt->id_kategori_produk)->get();

        return view('produk.edit_produk',$data);
    }


    public function updateProduk(Request $req)
    {
        
        if ($req->nama_barang == '') {
            http_response_code(400);
            exit(json_encode(['Message' => 'Nama Barang Harus Diisi']));
        }
        if ($req->harga_beli == '') {
            http_response_code(400);
            exit(json_encode(['Message' => 'Harga Beli Harus Diisi']));
        }
        if ($req->harga_jual == '') {
            http_response_code(400);
            exit(json_encode(['Message' => 'Harga Jual Harus Diisi']));
        }
        if ($req->deskripsi_produk == '') {
            http_response_code(400);
            exit(json_encode(['Message' => 'Deskripsi Barang Harus Diisi']));
        }

        $allowed_ext= array('image/jpg','image/jpeg','image/png','image/gif');

        $foto1  = $_FILES['fileuploadInput1'];
        $foto2  = $_FILES['fileuploadInput2'];
        $foto3  = $_FILES['fileuploadInput3'];
        $foto4  = $_FILES['fileuploadInput4'];

        // $arrdtFoto = explode(';',$req->gambar);
        // $dtFoto = '';


        // if ($foto1['size'] > 0) {
        //     $foto = $foto1;
        //     $file_name =$foto['name'];
        //     $file_ext = $foto['type'];
        //     $file_size=$foto['size'];
        //     $file_tmp= $foto['tmp_name'];
        //     $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
        //     $data = file_get_contents($file_tmp);
        //     $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            
        //     if(in_array($file_ext,$allowed_ext) === false)
        //     {
        //         http_response_code(400);
        //         exit(json_encode(['Message' => 'Type File Foto 1 Tidak Diijinkan']));
        //     }
        //     if($file_size > 1100000)
        //     {
        //         http_response_code(400);
        //         exit(json_encode(['Message' => 'Ukuran Foto 1 Maksimal 1mb']));
        //     }
            
        //     $imageName = time().'.'.$req->fileuploadInput1->extension();
        //     $req->fileuploadInput1->move(public_path('images'), $imageName);

        //     if ($dtFoto != '') {
        //         $dtFoto .= $dtFoto.';'.$imageName;
        //     }else{
        //         $dtFoto = $imageName;
        //     }
        // }

        // if ($foto2['size'] > 0) {
        //     $foto = $foto2;
        //     $file_name =$foto['name'];
        //     $file_ext = $foto['type'];
        //     $file_size=$foto['size'];
        //     $file_tmp= $foto['tmp_name'];
        //     $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
        //     $data = file_get_contents($file_tmp);
        //     $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            
        //     if(in_array($file_ext,$allowed_ext) === false)
        //     {
        //         http_response_code(400);
        //         exit(json_encode(['Message' => 'Type File Foto 2 Tidak Diijinkan']));
        //     }
        //     if($file_size > 1100000)
        //     {
        //         http_response_code(400);
        //         exit(json_encode(['Message' => 'Ukuran Foto 2 Maksimal 1mb']));
        //     }

        //     $imageName = time().'.'.$req->fileuploadInput2->extension();
        //     $req->fileuploadInput2->move(public_path('images'), $imageName);

        //     if ($dtFoto != '') {
        //         $dtFoto .= $dtFoto.';'.$imageName;
        //     }else{
        //         $dtFoto = $imageName;
        //     }
        // }

        // if ($foto3['size'] > 0) {
        //     $foto = $foto3;
        //     $file_name =$foto['name'];
        //     $file_ext = $foto['type'];
        //     $file_size=$foto['size'];
        //     $file_tmp= $foto['tmp_name'];
        //     $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
        //     $data = file_get_contents($file_tmp);
        //     $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            
        //     if(in_array($file_ext,$allowed_ext) === false)
        //     {
        //         http_response_code(400);
        //         exit(json_encode(['Message' => 'Type File Foto 3 Tidak Diijinkan']));
        //     }
        //     if($file_size > 1100000)
        //     {
        //         http_response_code(400);
        //         exit(json_encode(['Message' => 'Ukuran Foto 3 Maksimal 1mb']));
        //     }

        //     $imageName = time().'.'.$req->fileuploadInput3->extension();
        //     $req->fileuploadInput3->move(public_path('images'), $imageName);

        //     if ($dtFoto != '') {
        //         $dtFoto .= $dtFoto.';'.$imageName;
        //     }else{
        //         $dtFoto = $imageName;
        //     }
        // }

        // if ($foto4['size'] > 0) {
        //     $foto = $foto4;
        //     $file_name =$foto['name'];
        //     $file_ext = $foto['type'];
        //     $file_size=$foto['size'];
        //     $file_tmp= $foto['tmp_name'];
        //     $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
        //     $data = file_get_contents($file_tmp);
        //     $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            
        //     if(in_array($file_ext,$allowed_ext) === false)
        //     {
        //         http_response_code(400);
        //         exit(json_encode(['Message' => 'Type File Foto 4 Tidak Diijinkan']));
        //     }
        //     if($file_size > 1100000)
        //     {
        //         http_response_code(400);
        //         exit(json_encode(['Message' => 'Ukuran Foto 4 Maksimal 1mb']));
        //     }

        //     $imageName = time().'.'.$req->fileuploadInput4->extension();
        //     $req->fileuploadInput4->move(public_path('images'), $imageName);

        //     if ($dtFoto != '') {
        //         $dtFoto .= $dtFoto.';'.$imageName;
        //     }else{
        //         $dtFoto = $imageName;
        //     }
        // }

        $dtHeadInsert['id_produk_perusahaan'] = '0';
        $dtHeadInsert['id_kategori_produk'] = $req->kategori_produk;
        $dtHeadInsert['id_kategori_produk_sub'] = $req->sub_kategori_produk;
        $dtHeadInsert['id_reseller'] = $this->getDataToko()->id_reseller;
        $dtHeadInsert['nama_produk'] = $req->nama_barang;
        $dtHeadInsert['produk_seo'] = str_replace(" ", "_", $req->nama_barang);
        $dtHeadInsert['satuan'] = $req->satuan;
        $dtHeadInsert['harga_beli'] = $req->harga_beli;
        $dtHeadInsert['harga_reseller'] = ($req->harga_reseller >= 0)?$req->harga_reseller:0;
        $dtHeadInsert['harga_konsumen'] = ($req->harga_jual >= 0)?$req->harga_jual:0;
        $dtHeadInsert['harga_premium'] = (isset($req->harga_premium) && $req->harga_premium >= 0)?$req->harga_premium:0;
        $dtHeadInsert['harga_platform_persen'] = ($req->harga_platform >= 0)?$req->harga_platform:0;
        $dtHeadInsert['harga_platform'] = ($req->harga_platform >= 0)?$req->harga_jual-(($req->harga_jual*$req->harga_platform)/100):0;
        // $dtHeadInsert['harga_level_newbie'] = ($req->harga_level_newbie >= 0)?$req->harga_level_newbie:0;
        // $dtHeadInsert['harga_level_pedagang'] = ($req->harga_level_pedagang >= 0)?$req->harga_level_pedagang:0;
        // $dtHeadInsert['harga_level_juragan'] = ($req->harga_level_juragan >= 0)?$req->harga_level_juragan:0;
        // $dtHeadInsert['harga_level_big'] = ($req->harga_level_big >= 0)?$req->harga_level_big:0;
        // $dtHeadInsert['harga_level_bos'] = ($req->harga_level_bos >= 0)?$req->harga_level_bos:0;
        $dtHeadInsert['berat'] = $req->berat;
        $dtHeadInsert['username'] = $this->getDataToko()->id_konsumen;
        $dtHeadInsert['aktif'] = 'D';
        $dtHeadInsert['revisi_feedback'] = $req->catatan_revisi;
        $dtHeadInsert['tag'] = '';
        $dtHeadInsert['minimum'] = '1';
        $dtHeadInsert['sku'] = $req->barcode;
        $dtHeadInsert['fee_produk'] = '0';
        $dtHeadInsert['jenis_produk'] = 'Fisik';
        $dtHeadInsert['waktu_input'] = date('Y-m-d H:i:s');
        $dtHeadInsert['source'] = 'POS';
        // $dtHeadInsert['gambar'] = $dtFoto;
        $dtHeadInsert['keterangan'] = '-';
        $dtHeadInsert['tentang_produk'] = $req->deskripsi_produk;

        $id_produk = $req->id_barang;

        if ($req->type_variasi) {
            if (count($req->type_variasi) != count($req->penambah_harga_variasi) && count($req->type_variasi) != count($req->variasi) && count($req->type_variasi) != count($req->penambah_harga_platform) && count($req->type_variasi) != count($req->penambah_harga_newbie) && count($req->type_variasi) != count($req->penambah_harga_pedagang) && count($req->type_variasi) != count($req->penambah_harga_juragan) && count($req->type_variasi) != count($req->penambah_harga_big) && count($req->type_variasi) != count($req->penambah_harga_bos)  ) {
                http_response_code(400);
                exit(json_encode(['Message' => 'Ada Kesalahan di Pengisian Variasi']));
            }
            
            if (in_array(null, $req->type_variasi) && in_array(null, $req->penambah_harga_variasi) && in_array(null, $req->variasi) && in_array(null, $req->penambah_harga_platform) && in_array(null, $req->penambah_harga_newbie) && in_array(null, $req->penambah_harga_pedagang) && in_array(null, $req->penambah_harga_juragan) && in_array(null, $req->penambah_harga_big) && in_array(null, $req->penambah_harga_bos) ) {
                http_response_code(400);
                exit(json_encode(['Message' => 'Ada Kesalahan di Pengisian Variasi']));
            }
            
            
            DB::table('rb_produk')->where('id_produk',$id_produk)->update($dtHeadInsert);

            DB::table('rb_produk_variasi')->where('id_produk', $id_produk)->whereNotIn('id_variasi',$req->id_variasi)->delete();
            for ($i=0; $i < count($req->type_variasi); $i++) { 

                $dtVariasi['id_produk'] = $id_produk;
                $dtVariasi['nama'] = $req->type_variasi[$i];
                $dtVariasi['variasi'] = $req->variasi[$i];
                $dtVariasi['variasi_harga'] = $req->penambah_harga_variasi[$i];
                // $dtVariasi['harga_platform'] = ($req->penambah_harga_platform[$i] >= 0)?$req->penambah_harga_platform[$i]:0;
                // $dtVariasi['harga_level_newbie'] = ($req->penambah_harga_newbie[$i] >= 0)?$req->penambah_harga_newbie[$i]:0;
                // $dtVariasi['harga_level_pedagang'] = ($req->penambah_harga_pedagang[$i] >= 0)?$req->penambah_harga_pedagang[$i]:0;
                // $dtVariasi['harga_level_juragan'] = ($req->penambah_harga_juragan[$i] >= 0)?$req->penambah_harga_juragan[$i]:0;
                // $dtVariasi['harga_level_big'] = ($req->penambah_harga_big[$i] >= 0)?$req->penambah_harga_big[$i]:0;
                // $dtVariasi['harga_level_bos'] = ($req->penambah_harga_bos[$i] >= 0)?$req->penambah_harga_bos[$i]:0;

                if (in_array(null, $req->id_variasi)) {
                    DB::table('rb_produk_variasi')->insert($dtVariasi);
                } else {
                    DB::table('rb_produk_variasi')->where('id_variasi', $req->id_variasi[$i])->update($dtVariasi);
                }
                
                
            }
            
        } else {
            DB::table('rb_produk_variasi')->where('id_produk', $id_produk)->delete();
            DB::table('rb_produk')->where('id_produk',$id_produk)->update($dtHeadInsert);
        }

        http_response_code(200);
        exit(json_encode(['Message' => 'Berhasil Menyimpan Barang']));
    }


}