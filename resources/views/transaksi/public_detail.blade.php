@extends('layout.template')

@section('title', 'Detail Transaksi')

@section('content')

@if(!$dt_header)
<div class="det-card" style="margin:16px; padding:16px">Transaksi tidak ditemukan</div>
@else
<div class="det-card" style="margin:16px; padding:16px">
    <h3>Detail Transaksi: {{ $dt_header->kode_transaksi }}</h3>
    <p>Waktu: {{ date('d M Y H:i', strtotime($dt_header->waktu_transaksi)) }}</p>
    <p>Nama: {{ $dt_header->nama_lengkap ?? 'Konsumen Umum' }}</p>
    <p>No HP: {{ $dt_header->no_hp ?? '-' }}</p>
    <hr>
    <h4>Items</h4>
    <ul>
        @foreach($dt_detail as $d)
        <li>{{ $d->jumlah }} x {{ $d->nama_produk }} — Rp{{ number_format($d->harga_jual) }}</li>
        @endforeach
    </ul>
    <hr>
    <p><strong>Total:</strong> Rp{{ number_format($dt_header->total ?? 0) }}</p>
</div>
@endif

@endsection
