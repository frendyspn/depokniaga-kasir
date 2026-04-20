@extends('layout.template')

@section('title', $title)

@section('content')

@php
    // Status transaksi dari proses
    $prosesMap = [
        '0' => ['label' => 'Baru',         'color' => '#6c757d', 'bg' => '#f0f0f0'],
        '1' => ['label' => 'Diproses',     'color' => '#1565c0', 'bg' => '#e3f2fd'],
        '2' => ['label' => 'Dikirim',      'color' => '#00838f', 'bg' => '#e0f7fa'],
        '3' => ['label' => 'Dikonfirmasi', 'color' => '#e65100', 'bg' => '#fff3e0'],
        '4' => ['label' => 'Selesai',      'color' => '#2e7d32', 'bg' => '#e8f5e9'],
        '5' => ['label' => 'Dikembalikan', 'color' => '#f57f17', 'bg' => '#fffde7'],
        'x' => ['label' => 'Dibatalkan',   'color' => '#c62828', 'bg' => '#ffebee'],
    ];
    $proses = $prosesMap[$dt_header->proses ?? ''] ?? ['label' => $dt_header->proses ?? '-', 'color' => '#666', 'bg' => '#f0f0f0'];

    // Metode pengiriman
    $metodeMap = ['tanpa_ongkir' => 'Tanpa Ongkir', 'ongkir_toko' => 'Antar Toko', 'ongkir_lokal' => 'Kurir Lokal'];
    $metode = $metodeMap[$dt_header->kurir ?? ''] ?? ($dt_header->kurir == '-' ? 'Tanpa Ongkir' : ($dt_header->kurir ?? '-'));

    // Kalkulasi
    $subtotal = 0;
    foreach ($dt_detail as $d) {
        $subtotal += $d->jumlah * ($d->harga_jual - ($d->diskon ?? 0));
    }
    $ongkir  = $dt_header->ongkir ?? 0;
    $total   = $subtotal + $ongkir;

    // Alamat pengiriman
    $alamat = $dt_header->alamat_pengiriman ?: ($dt_header->alamat_lengkap ?? '-');

    // Status kurir
    $shipMap = [
        'PENDING' => ['label' => 'Menunggu',        'color' => '#e65100', 'bg' => '#fff3e0'],
        'SEARCH'  => ['label' => 'Mencari Driver',  'color' => '#f57f17', 'bg' => '#fffde7'],
        'PICKUP'  => ['label' => 'Diambil Kurir',   'color' => '#1565c0', 'bg' => '#e3f2fd'],
        'FINISH'  => ['label' => 'Terkirim',        'color' => '#2e7d32', 'bg' => '#e8f5e9'],
        'CANCEL'  => ['label' => 'Dibatalkan',      'color' => '#c62828', 'bg' => '#ffebee'],
    ];
    $dtKurir = $dt_kurir ?? null;
    $shipInfo = $dtKurir ? ($shipMap[$dtKurir->status ?? ''] ?? ['label' => $dtKurir->status, 'color' => '#666', 'bg' => '#f0f0f0']) : null;
@endphp

<style>
.det-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.07);
    margin: 0 16px 12px;
    overflow: hidden;
}
.det-section-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.8px;
    color: #999;
    text-transform: uppercase;
    padding: 0 16px;
    margin: 16px 0 6px;
}
.det-row {
    display: flex;
    align-items: flex-start;
    padding: 11px 16px;
    border-bottom: 1px solid #f4f4f4;
}
.det-row:last-child { border-bottom: none; }
.det-label {
    font-size: 12px;
    color: #999;
    min-width: 90px;
    padding-top: 1px;
}
.det-value {
    font-size: 13px;
    color: #222;
    flex: 1;
    text-align: right;
    font-weight: 500;
}
.det-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.det-item-row {
    padding: 13px 16px;
    border-bottom: 1px solid #f4f4f4;
}
.det-item-row:last-child { border-bottom: none; }
.det-item-name { font-size: 14px; font-weight: 500; color: #222; }
.det-item-meta { font-size: 12px; color: #999; margin-top: 3px; }
.det-item-price { font-size: 14px; font-weight: 600; color: #222; white-space: nowrap; }
.det-total-row {
    display: flex;
    justify-content: space-between;
    padding: 14px 16px;
    border-top: 2px solid #f0f0f0;
}
</style>

<div id="appCapsule" style="background:#f7f8fa; min-height:100vh; padding-bottom:24px">

    {{-- ── Header ── --}}
    <div class="det-card" style="margin-top:16px">
        <div style="padding:18px 16px">
            <div style="display:flex; justify-content:space-between; align-items:flex-start">
                <div>
                    <p style="font-size:11px; color:#999; margin:0 0 4px; letter-spacing:0.5px">KODE TRANSAKSI</p>
                    <p style="font-size:16px; font-weight:700; color:#111; margin:0 0 6px">{{ $dt_header->kode_transaksi }}</p>
                    <p style="font-size:12px; color:#999; margin:0">{{ date('d M Y, H:i', strtotime($dt_header->waktu_transaksi)) }}</p>
                </div>
                <span class="det-badge" style="color:{{ $proses['color'] }}; background:{{ $proses['bg'] }}">
                    {{ $proses['label'] }}
                </span>
            </div>
        </div>
    </div>

    {{-- ── Konsumen ── --}}
    <p class="det-section-label">Konsumen</p>
    <div class="det-card">
        <div class="det-row">
            <span class="det-label">Nama</span>
            <span class="det-value">{{ $dt_header->nama_lengkap ?? 'Konsumen Umum' }}</span>
        </div>
        @if($dt_header->no_hp ?? null)
        <div class="det-row">
            <span class="det-label">No. HP</span>
            <span class="det-value">{{ $dt_header->no_hp }}</span>
        </div>
        @endif
        @if($alamat && $alamat != '-')
        <div class="det-row" style="align-items:flex-start">
            <span class="det-label">Alamat</span>
            <span class="det-value" style="text-align:right; line-height:1.5">{{ $alamat }}</span>
        </div>
        @endif
        <div class="det-row">
            <span class="det-label">Pengiriman</span>
            <span class="det-value">{{ $metode }}</span>
        </div>
    </div>

    {{-- ── Pesanan ── --}}
    <p class="det-section-label">Pesanan</p>
    <div class="det-card">
        @foreach($dt_detail as $dtl)
        @php $itemTotal = $dtl->jumlah * ($dtl->harga_jual - ($dtl->diskon ?? 0)); @endphp
        <div class="det-item-row">
            <div style="display:flex; justify-content:space-between; align-items:flex-start">
                <span class="det-item-name" style="flex:1; margin-right:8px">{{ $dtl->nama_produk }}</span>
                <span class="det-item-price">Rp{{ number_format($itemTotal) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; margin-top:4px">
                <span class="det-item-meta">
                    {{ $dtl->jumlah }} {{ $dtl->satuan }} &times; Rp{{ number_format($dtl->harga_jual - ($dtl->diskon ?? 0)) }}
                </span>
                @if(($dtl->diskon ?? 0) > 0)
                <span style="font-size:11px; color:#e53935">Hemat Rp{{ number_format($dtl->diskon * $dtl->jumlah) }}</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Ringkasan Pembayaran ── --}}
    <p class="det-section-label">Pembayaran</p>
    <div class="det-card">
        <div class="det-row">
            <span class="det-label">Subtotal</span>
            <span class="det-value">Rp{{ number_format($subtotal) }}</span>
        </div>
        @if($ongkir > 0)
        <div class="det-row">
            <span class="det-label">Ongkir</span>
            <span class="det-value">Rp{{ number_format($ongkir) }}</span>
        </div>
        @endif
        <div class="det-total-row">
            <span style="font-size:15px; font-weight:700; color:#111">Total</span>
            <span style="font-size:16px; font-weight:700; color:#1565c0">Rp{{ number_format($total) }}</span>
        </div>
        @if($dt_header->metode_pembayaran ?? null)
        <div class="det-row" style="border-top:1px solid #f4f4f4">
            <span class="det-label">Metode</span>
            <span class="det-value">{{ $dt_header->metode_pembayaran }}</span>
        </div>
        @endif
        @if(($dt_header->status_pembayaran ?? null))
        <div class="det-row">
            <span class="det-label">Status</span>
            <span class="det-value">{{ $dt_header->status_pembayaran }}</span>
        </div>
        @endif
    </div>

    {{-- ── Pengiriman / Kurir ── --}}
    @if($dtKurir)
    <p class="det-section-label">Pengiriman</p>
    <div class="det-card">
        <div class="det-row" style="align-items:center">
            <span class="det-label">Status</span>
            <span class="det-value">
                <span class="det-badge" style="color:{{ $shipInfo['color'] }}; background:{{ $shipInfo['bg'] }}">
                    {{ $shipInfo['label'] }}
                </span>
            </span>
        </div>
        @if($dtKurir->kode_order ?? null)
        <div class="det-row">
            <span class="det-label">Kode Order</span>
            <span class="det-value">{{ $dtKurir->kode_order }}</span>
        </div>
        @endif
        @if(($dtKurir->tarif ?? 0) > 0)
        <div class="det-row">
            <span class="det-label">Tarif</span>
            <span class="det-value">Rp{{ number_format($dtKurir->tarif) }}</span>
        </div>
        @endif
        @if($dtKurir->nama_sopir ?? null)
        <div class="det-row">
            <span class="det-label">Kurir</span>
            <span class="det-value" style="font-weight:600">{{ $dtKurir->nama_sopir }}</span>
        </div>
        @endif
        @if($dtKurir->plat_nomor ?? null)
        <div class="det-row">
            <span class="det-label">Kendaraan</span>
            <span class="det-value">{{ $dtKurir->merek }} &middot; {{ $dtKurir->plat_nomor }}</span>
        </div>
        @endif
    </div>
    @endif

    {{-- ── APPS: Confirm / Cancel ── --}}
    @if(($dt_header->source ?? '') == 'APPS' && ($dt_header->status ?? '') == 'PENDING')
    <div class="det-card" style="padding:12px 16px">
        <div style="display:flex; gap:10px">
            <a class="btn btn-primary" style="flex:1" href="{{url('transaksi/confirm/'.$dt_header->id.'/NEW')}}">{{__('bahasa.ambil_order')}}</a>
            <button class="btn btn-danger" style="flex:1" data-bs-toggle="collapse" data-bs-target="#accordion003">{{__('bahasa.batal')}}</button>
        </div>
        <div id="accordion003" class="accordion-collapse collapse mt-2">
            <form action="{{route('transaksi/cancel_transaksi')}}" method="post">
                @csrf
                <input type="hidden" name="batal_id" value="{{$dt_header->id}}">
                <textarea class="form-control" name="batal_alasan" placeholder="Alasan Pembatalan" required rows="3"></textarea>
                <button type="submit" class="btn btn-secondary btn-block mt-2">Batalkan</button>
            </form>
        </div>
    </div>
    @endif

    {{-- ── APPS: Timeline ── --}}
    @if(($dt_header->source ?? '') == 'APPS' && ($dt_header->status ?? '') != 'PENDING')
    <p class="det-section-label">
        {{__('bahasa.status_order')}}
        <span style="color:#1565c0; cursor:pointer; margin-left:8px" onclick="location.reload()">{{__('bahasa.refresh')}}</span>
    </p>
    <div class="det-card" style="padding:16px">
        <div class="timeline ms-3">
            @foreach($timeline as $row)
            @php
                $tlStatus = match($row->log_status) {
                    'NEW','PROCESS' => __('bahasa.mencari_driver'),
                    'ONTHEWAY'      => __('bahasa.pesanan_sedang_diantar'),
                    'FINISH'        => __('bahasa.pesanan_sampai'),
                    'PENDING'       => __('bahasa.pesanan_konfirm_toko'),
                    default         => $row->log_status,
                };
            @endphp
            <div class="item">
                <div class="dot {{$warna_timeline[rand(0,count($warna_timeline)-1)]}}"></div>
                <div class="content">
                    <h4 class="title">{{$tlStatus}}</h4>
                    <div class="text">{{date('d M Y H:i:s', strtotime($row->log_time))}}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@endsection
