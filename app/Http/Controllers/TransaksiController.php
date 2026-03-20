<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App;
use DB;
use Illuminate\Support\Facades\Http;

class TransaksiController extends Controller
{
    public function index(Request $req)
    {
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }
        
        $data['title'] = __('bahasa.transaksi');
        $data['header'] = 'goback';
        $data['menu'] = '';

        $data['list_transaksi'] = DB::table('kurir_order as a')
        ->select('a.id', 'a.alamat_antar', 'a.tanggal_order', 'a.jenis_layanan', 'a.source', 'a.status', 'b.nama_lengkap as nama_pemesan', 
        DB::raw('case when a.JENIS_LAYANAN = "SHOP" then (select sum(jumlah*(harga_jual-diskon)) from rb_penjualan_shop where id_kurir_order = a.id) else (select sum(jumlah*(harga_jual-diskon)) from rb_penjualan_detail where id_penjualan = a.id_penjualan) end as total_belanja')
        )
        ->leftJoin('rb_konsumen as b', 'b.id_konsumen', 'a.id_pemesan')
        ->where('a.SOURCE', 'APPS')
        ->where('a.id_reseller', $this->getDataToko()->id_reseller)
        ->where('a.status', 'PENDING')
        ->whereIn('a.JENIS_LAYANAN', ['SHOP','FOOD'])
        ->get();

        return view('transaksi.transaksi',$data);
    }

    public function semuaTransaksi(Request $req)
    {
        App::setLocale(session()->get('locale'));

        if (!cek_login()){
            return redirect('/login');
        }

        $data['title'] = __('bahasa.transaksi');
        $data['header'] = 'goback';
        $data['menu'] = '';

        $dt_apps = DB::table('kurir_order as a')
        ->select('a.id', 'a.alamat_antar', 'a.tanggal_order', 'a.jenis_layanan', 'a.source', 'a.status', 'b.nama_lengkap as nama_pemesan', 
        DB::raw('case when a.JENIS_LAYANAN = "SHOP" then (select sum(jumlah*(harga_jual-diskon)) from rb_penjualan_shop where id_kurir_order = a.id) else (select sum(jumlah*(harga_jual-diskon)) from rb_penjualan_detail where id_penjualan = a.id_penjualan) end as total_belanja')
        )
        ->leftJoin('rb_konsumen as b', 'b.id_konsumen', 'a.id_pemesan')
        ->where('a.SOURCE', 'APPS')
        ->where('a.id_reseller', $this->getDataToko()->id_reseller)
        ->whereIn('a.JENIS_LAYANAN', ['SHOP','FOOD']);

        $data['list_transaksi'] = DB::table('rb_penjualan as a')
        ->select('a.id_penjualan as id', 'b.alamat_lengkap as alamat_antar', 'a.waktu_transaksi as tanggal_order', DB::raw('"POS" as jenis_layanan'), DB::raw('"POS" as source'), DB::raw('"" as status'), 'b.nama_lengkap as nama_pemesan',
        DB::raw('(select sum(jumlah*(harga_jual-diskon)) from rb_penjualan_detail where id_penjualan = a.id_penjualan) as total_belanja')
        )
        ->leftJoin('rb_konsumen as b', 'b.id_konsumen', 'a.id_pembeli')
        ->where('a.id_penjual', $this->getDataToko()->id_reseller)
        ->union($dt_apps)
        ->get();

        return view('transaksi.transaksi',$data);
    }

    public function getTransaksi($app, $service, $id)
    {
        App::setLocale(session()->get('locale'));
        $id = base64_decode($id);

        if ($app == 'APPS') {
            if ($service == 'SHOP') {
                $penjualan_header = DB::table('kurir_order as a')->leftJoin('rb_konsumen as b', 'b.id_konsumen', 'a.id_pemesan')->where('a.id', $id)->first();
                $penjualan_detail = DB::table('rb_penjualan_shop')->where('id_kurir_order', $id)->get();
                $kode_trans = $penjualan_header->kode_order;
            }
        } else {
            $penjualan_header = DB::table('rb_penjualan as a')->select('a.*', 'b.nama_lengkap', 'b.alamat_lengkap as alamat_antar', DB::raw('"POS" as jenis_layanan'), DB::raw('"POS" as source'))->leftJoin('rb_konsumen as b', 'b.id_konsumen', 'a.id_pembeli')->where('a.id_penjualan', $id)->first();
            $penjualan_detail = DB::table('rb_penjualan_detail as a')->select('a.*', 'b.nama_produk')->leftJoin('rb_produk as b', 'b.id_produk', 'a.id_produk')->where('a.id_penjualan', $id)->get();
            $kode_trans = '';
        }
        
        $data['dt_header'] = $penjualan_header;
        $data['dt_detail'] = $penjualan_detail;

        $data['timeline'] = DB::table('kurir_order_log')->where('id_order', $id)->whereNotIn('log_status', ['PENDING','NEW'])->get();
        $data['warna_timeline'] = ['bg-primary','bg-info','bg-danger','bg-warning'];
        

        $data['title'] = __('bahasa.transaksi').' '.$kode_trans;
        $data['header'] = 'goback';
        $data['menu'] = '';

        return view('transaksi.detail_transaksi',$data);
    }

    public function confirmOrder($id, $status)
    {
        $dt_update['status'] = $status;
        DB::table('kurir_order')->where('id',$id)->update($dt_update);

        return redirect('transaksi');
    }

    public function cancelOrder(Request $req)
    {
        $dt_update['alasan_pembatalan'] = $req->batal_alasan;
        $dt_update['status'] = 'CANCEL';
        $id = $req->batal_id;

        DB::table('kurir_order')->where('id',$id)->update($dt_update);

        return redirect('transaksi');
    }
}