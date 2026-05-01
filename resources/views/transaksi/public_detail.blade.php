@extends('layout.template')

@section('title', 'Detail Transaksi')

@section('content')

@php
    $paymentUrl = $paymentUrl ?? null;
@endphp

<style>
.public-pay-wrap{background:linear-gradient(180deg,#f7f9fc 0%,#eef4ff 100%); min-height:100vh; padding:16px}
.public-card{background:#fff; border-radius:18px; box-shadow:0 10px 30px rgba(16,24,40,.08); padding:18px; margin-bottom:14px}
.public-title{font-size:20px; font-weight:800; color:#12324a; margin:0 0 6px}
.public-sub{font-size:13px; color:#64748b; margin:0}
.public-bank-list{display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:12px; margin-top:12px}
.public-bank-btn{border:1px solid #e5eaf2; border-radius:14px; background:#fff; padding:14px; text-align:center; box-shadow:0 2px 8px rgba(15,23,42,.04); transition:all .15s ease; cursor:pointer}
.public-bank-btn:hover{transform:translateY(-3px); box-shadow:0 12px 24px rgba(15,23,42,.08); border-color:#bfd2ff}
.public-bank-btn img{height:36px; width:auto; display:block; margin:0 auto 8px}
.public-bank-btn .name{font-weight:700; color:#10375c; font-size:13px; line-height:1.2}
.public-bank-btn .acc{font-size:12px; color:#6b7280; margin-top:4px}
.public-bank-btn.selected{border-color:#0d6efd; background:linear-gradient(180deg,#ffffff 0%,#f7fbff 100%); box-shadow:0 12px 28px rgba(13,110,253,.12)}
.public-summary{display:flex; justify-content:space-between; gap:12px; padding:10px 0; border-bottom:1px solid #eef2f7; font-size:13px}
.public-summary:last-child{border-bottom:none}
.public-label{color:#6b7280}
.public-value{color:#12263a; font-weight:600; text-align:right}
.public-btn{width:100%; border:none; border-radius:14px; padding:14px 16px; font-weight:800; background:linear-gradient(135deg,#0d6efd,#2f80ed); color:#fff; box-shadow:0 10px 22px rgba(13,110,253,.22)}
.public-btn:disabled{opacity:.65}
.public-note{font-size:12px; color:#64748b; margin-top:8px; text-align:center}
</style>

<div class="public-pay-wrap">
    @if(!$dt_header)
        <div class="public-card">
            <div class="public-title">Transaksi tidak ditemukan</div>
            <p class="public-sub">Link detail tidak valid atau sudah tidak tersedia.</p>
        </div>
    @else
        <div class="public-card">
            <div class="public-title">{{ $dt_header->kode_transaksi }}</div>
            <p class="public-sub">Pilih bank transfer untuk melanjutkan ke halaman pembayaran Moota.</p>
        </div>

        <div class="public-card">
            <div class="public-summary">
                <span class="public-label">Nama</span>
                <span class="public-value">{{ $dt_header->nama_lengkap ?? 'Konsumen Umum' }}</span>
            </div>
            <div class="public-summary">
                <span class="public-label">No. HP</span>
                <span class="public-value">{{ $dt_header->no_hp ?? '-' }}</span>
            </div>
            <div class="public-summary">
                <span class="public-label">Total</span>
                <span class="public-value">Rp{{ number_format($dt_header->total ?? 0) }}</span>
            </div>
        </div>

        <div class="public-card">
            <div class="public-title" style="font-size:17px">Metode Pembayaran</div>
            <p class="public-sub">Klik salah satu bank di bawah ini. Anda akan langsung diarahkan ke halaman pembayaran setelah diproses.</p>

            @if($paymentUrl)
                <div class="public-card" style="margin-top:12px; padding:14px; background:#f8fbff; border:1px solid #d9e7ff; box-shadow:none">
                    <p class="public-sub" style="margin:0 0 10px; color:#0f4c81">Pembayaran sudah dibuat. Klik tombol berikut untuk melanjutkan.</p>
                    <a href="{{ $paymentUrl }}" class="public-btn" style="display:block; text-align:center; text-decoration:none">Lanjut ke Pembayaran</a>
                </div>
            @else
                <form id="publicPayForm" method="POST" action="{{ route('transaksi.public_bayar', ['token' => $token]) }}">
                    @csrf
                    <input type="hidden" name="account_id" id="account_id">
                    <div id="public_bank_list" class="public-bank-list">
                        <div class="public-sub" style="grid-column:1/-1; text-align:center">Memuat bank...</div>
                    </div>
                    <button type="submit" id="publicPayBtn" class="public-btn" disabled style="margin-top:14px">Lanjut ke Pembayaran</button>
                    <div class="public-note">Setelah dipilih, Anda akan diarahkan ke Moota payment link.</div>
                </form>
            @endif
        </div>

        <div class="public-card">
            <div class="public-title" style="font-size:17px">Rincian Item</div>
            @foreach($dt_detail as $d)
                <div class="public-summary">
                    <span class="public-label">{{ $d->nama_produk }} x {{ $d->jumlah }}</span>
                    <span class="public-value">Rp{{ number_format(($d->jumlah * $d->harga_jual) - ($d->diskon ?? 0)) }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>

@if($dt_header && !$paymentUrl)
<script>
var selectedBank = null;
var selectedAccountName = null;

function setSelectedBank(accountId, accountName, button) {
    selectedBank = accountId;
    selectedAccountName = accountName;
    document.getElementById('account_id').value = accountId;
    document.getElementById('publicPayBtn').disabled = false;

    var list = document.getElementById('public_bank_list');
    Array.from(list.children).forEach(function(child){ child.classList.remove('selected'); });
    if (button) button.classList.add('selected');
}

document.addEventListener('DOMContentLoaded', function(){
    fetch('/api/moota/accounts')
        .then(function(res){ if (!res.ok) throw new Error('HTTP ' + res.status); return res.json(); })
        .then(function(data){
            var list = document.getElementById('public_bank_list');
            list.innerHTML = '';
            if (data.error) {
                list.innerHTML = '<div class="public-sub" style="grid-column:1/-1; text-align:center; color:#c62828">'+(data.message || 'Gagal memuat bank')+'</div>';
                return;
            }

            var accounts = data.accounts || data.data || [];
            if (!accounts.length) {
                list.innerHTML = '<div class="public-sub" style="grid-column:1/-1; text-align:center">Tidak ada bank tersedia</div>';
                return;
            }

            accounts.forEach(function(a){
                var id = a.id ?? a.account_id ?? a.accountId ?? a.code ?? a['account_id'] ?? a['id'];
                var name = a.name ?? a.bank_name ?? a.account_name ?? 'Bank';
                var icon = a.icon ?? '';
                var accountNum = a.account_number ?? '';

                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'public-bank-btn';
                btn.innerHTML = (icon ? '<img src="'+icon+'" alt="'+name+'">' : '<i class="fas fa-university" style="font-size:28px;color:#6b7280"></i>') +
                    '<div class="name">'+name+'</div>' +
                    (accountNum ? '<div class="acc">'+accountNum+'</div>' : '');
                btn.onclick = function(){ setSelectedBank(id, name, btn); };
                list.appendChild(btn);
            });
        })
        .catch(function(err){
            console.error('[public detail] fetch accounts error', err);
            var list = document.getElementById('public_bank_list');
            list.innerHTML = '<div class="public-sub" style="grid-column:1/-1; text-align:center; color:#c62828">Gagal memuat bank</div>';
        });
});
</script>
@endif

@endsection
