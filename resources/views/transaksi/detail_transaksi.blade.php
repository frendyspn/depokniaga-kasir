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

    // Status pembayaran
    $paymentRaw = strtolower(trim((string)($dt_header->status_pembayaran ?? '')));
    $paymentMap = [
        ''            => ['label' => 'Belum Diatur', 'color' => '#666', 'bg' => '#f0f0f0'],
        'belum_lunas' => ['label' => 'Belum Lunas',  'color' => '#e65100', 'bg' => '#fff3e0'],
        'lunas'       => ['label' => 'Lunas',        'color' => '#2e7d32', 'bg' => '#e8f5e9'],
    ];
    $paymentInfo = $paymentMap[$paymentRaw] ?? ['label' => ucwords(str_replace('_', ' ', $paymentRaw)), 'color' => '#666', 'bg' => '#f0f0f0'];

    // Koordinat + link maps
    $kordinatPengiriman = trim((string)($dt_header->kordinat_pengiriman ?? ''));
    $mapsLink = '';
    if ($kordinatPengiriman !== '') {
        $mapsLink = filter_var($kordinatPengiriman, FILTER_VALIDATE_URL)
            ? $kordinatPengiriman
            : 'https://www.google.com/maps?q=' . urlencode($kordinatPengiriman);
    }
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

/* Moota bank button styles */
.moota-bank-list{display:grid; grid-template-columns:repeat(auto-fit, minmax(160px, 1fr)); gap:12px; align-items:start}
.moota-bank-btn{padding:14px; display:flex; flex-direction:column; align-items:center; gap:8px; border-radius:10px; border:1px solid #e9eef8; background:#ffffff; box-shadow:0 2px 6px rgba(15,23,42,0.04); transition:transform .12s, box-shadow .12s, border-color .12s; text-align:center}
.moota-bank-btn img{height:36px; width:auto; display:block}
.moota-bank-btn .bank-name{font-weight:700; font-size:13px; color:#10375c}
.moota-bank-btn .account-num{font-size:12px; color:#6b7280}
.moota-bank-btn:hover{transform:translateY(-4px); box-shadow:0 10px 30px rgba(16,55,92,0.08)}
.moota-bank-btn.selected{border-color:#0d6efd; box-shadow:0 10px 30px rgba(13,110,253,0.12); transform:translateY(-6px); background:linear-gradient(180deg, #ffffff 0%, #f7fbff 100%)}
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

    {{-- ── Konsumen + Pengiriman (side-by-side on md+) ── --}}
    <div class="row g-0" style="margin:0 16px 4px; gap:12px; flex-wrap:wrap">

        <div class="{{ $dtKurir ? 'col-12 col-md' : 'col-12' }}" style="min-width:0">
            <p class="det-section-label" style="padding:0; margin:12px 0 6px">Konsumen</p>
            <div class="det-card" style="margin:0; height:auto">
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
                @if($kordinatPengiriman !== '')
                <div class="det-row" style="align-items:flex-start">
                    <span class="det-label">Koordinat</span>
                    <span class="det-value" style="text-align:right; line-height:1.4">
                        {{ $kordinatPengiriman }}
                        @if($mapsLink)
                        <br>
                        <a href="{{ $mapsLink }}" target="_blank" rel="noopener" style="font-size:12px; color:#1565c0; text-decoration:underline;">Buka di Google Maps</a>
                        @endif
                    </span>
                </div>
                @endif
                <div class="det-row">
                    <span class="det-label">Pengiriman</span>
                    <span class="det-value">{{ $metode }}</span>
                </div>
            </div>
        </div>

        @if($dtKurir)
        <div class="col-12 col-md" style="min-width:0">
            <p class="det-section-label" style="padding:0; margin:12px 0 6px">Pengiriman</p>
            <div class="det-card" style="margin:0; height:auto">
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
        </div>
        @endif

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
        <div class="det-row">
            <span class="det-label">Status</span>
            <span class="det-value">
                <span class="det-badge" style="color:{{ $paymentInfo['color'] }}; background:{{ $paymentInfo['bg'] }}">
                    {{ $paymentInfo['label'] }}
                </span>
            </span>
        </div>
    </div>

    {{-- ── Moota Payment Status ── --}}
    @if(($dt_header->moota_status ?? null) !== null)
    <p class="det-section-label">Moota Payment</p>
    <div class="det-card">
        <div class="det-row">
            <span class="det-label">Status</span>
            <span class="det-value">
                <span class="det-badge" style="color:{{ ($dt_header->moota_status === 'success' ? '#2e7d32' : '#c62828') }}; background:{{ ($dt_header->moota_status === 'success' ? '#e8f5e9' : '#ffebee') }}">
                    {{ ucfirst($dt_header->moota_status) }}
                </span>
            </span>
        </div>
        @if($dt_header->moota_transaction_id ?? null)
        <div class="det-row">
            <span class="det-label">Transaction ID</span>
            <span class="det-value" style="font-size:12px; word-break:break-all">{{ $dt_header->moota_transaction_id }}</span>
        </div>
        @endif
        @if(!empty($dt_header->moota_bank_account_name) || !empty($dt_header->moota_bank_account_id))
        <div class="det-row">
            <span class="det-label">Bank</span>
            <span class="det-value" style="text-align:right">
                {{ $dt_header->moota_bank_account_name ?? $dt_header->moota_bank_account_id ?? '-' }}
            </span>
        </div>
        @endif
        @if($dt_header->moota_status === 'error')
        <div class="det-row" style="border-top:1px solid #f4f4f4; padding-top:12px; margin-bottom:12px">
            <div id="moota_accounts_detail" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:8px"></div>
            <button id="resend-moota-{{ $dt_header->id_penjualan }}" class="btn btn-sm btn-warning mb-2" style="width:100%" onclick="resendMootaConfirm({{ $dt_header->id_penjualan }})" disabled>
                <i class="fab fa-wordpress-simple"></i> Resend to Moota
            </button>
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

<div class="modal fade" id="modalResendMoota" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Pilih Bank untuk Pembayaran</h5></div>
      <div class="modal-body">
        <div id="moota_accounts_container" style="display:flex; gap:10px; flex-wrap:wrap; justify-content:center;">
          <p style="width:100%; text-align:center; color:#666;">Memuat daftar bank...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" id="btnConfirmResend" class="btn btn-primary" disabled>Kirim ke Moota</button>
      </div>
    </div>
  </div>
</div>

<script>
// Store selected bank for resend
var resend_selected_bank_id = null;
var resend_selected_bank_name = null;

function openResendModal(idPenjualan) {
    var container = document.getElementById('moota_accounts_container');
    container.innerHTML = '<p style="width:100%; text-align:center; color:#666;">Memuat daftar bank...</p>';
    
    fetch('/api/moota/accounts')
        .then(res => {
            if (!res.ok) {
                console.error('HTTP Error:', res.status, res.statusText);
                throw new Error('HTTP ' + res.status);
            }
            return res.json();
        })
        .then(data => {
            console.log('[openResendModal] Response data:', data);
            
            // Check if error response
            if (data.error) {
                console.error('[openResendModal] Error response:', data.message, data.attempts);
                container.innerHTML = '<p style="width:100%; color:#c62828;">Error: ' + (data.message || 'Gagal mengambil akun') + '</p>';
                var modal = new bootstrap.Modal(document.getElementById('modalResendMoota'));
                modal.show();
                return;
            }
            
            var accounts = data.accounts || data.data || [];
            container.innerHTML = '';
            
            if (!accounts || accounts.length === 0) {
                container.innerHTML = '<p style="width:100%; text-align:center; color:#666;">Tidak ada akun bank tersedia</p>';
            } else {
                accounts.forEach(function(a){
                    var id = a.id ?? a.account_id ?? a.accountId ?? a.code ?? a['account_id'] ?? a['id'];
                    var name = a.name ?? a.bank_name ?? a.account_name ?? 'Bank';
                    var icon = a.icon ?? '';
                    var accountNum = a.account_number ?? '';
                    
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn btn-outline-primary';
                    btn.style.cssText = 'flex:0 1 150px; padding:15px; display:flex; flex-direction:column; align-items:center; gap:8px; border:2px solid; cursor:pointer; transition:all 0.2s';
                    btn.id = 'resend-bank-btn-' + id;
                    
                    var iconHtml = icon ? '<img src="' + icon + '" style="height:30px; width:auto">' : '<i class="fas fa-university" style="font-size:24px"></i>';
                    var textHtml = '<span style="font-weight:600; font-size:13px; text-align:center">' + name + '</span>';
                    if (accountNum) textHtml += '<span style="font-size:11px; color:#666">' + accountNum + '</span>';
                    
                    btn.innerHTML = iconHtml + textHtml;
                    
                    btn.onclick = function(e) {
                        e.preventDefault();
                        selectResendBank(id, name, this);
                    };
                    
                    container.appendChild(btn);
                });
            }
            
            var modal = new bootstrap.Modal(document.getElementById('modalResendMoota'));
            modal.show();
            
            document.getElementById('btnConfirmResend').onclick = function(){
                resendMootaConfirm(idPenjualan);
            };
        })
        .catch(err => {
            console.error('[openResendModal] Fetch error:', err);
            container.innerHTML = '<p style="width:100%; color:#c62828;">Error: Gagal mengambil akun</p>';
            var modal = new bootstrap.Modal(document.getElementById('modalResendMoota'));
            modal.show();
        });
}

function selectResendBank(bankId, bankName, btnElement) {
    // Update global selections
    resend_selected_bank_id = bankId;
    resend_selected_bank_name = bankName;
    
    // Update button styling
    var container = document.getElementById('moota_accounts_container');
    if (container) {
        Array.from(container.children).forEach(function(btn){
            btn.classList && btn.classList.remove('selected');
        });
    }
    
    if (btnElement) {
        btnElement.classList.add('selected');
    }
    
    // Enable confirm button
    var confirmBtn = document.getElementById('btnConfirmResend');
    if (confirmBtn) confirmBtn.disabled = false;
}

function resendMootaConfirm(idPenjualan) {
    var accountId = resend_selected_bank_id;
    if (!accountId) {
        alert('Pilih bank terlebih dahulu');
        return;
    }
    var modal = bootstrap.Modal.getInstance(document.getElementById('modalResendMoota'));
    if (modal) modal.hide();
    resendMoota(idPenjualan, accountId);
}

function resendMoota(idPenjualan, accountId) {
    if (!confirm('Resend transaksi ini ke Moota?')) return;
    
    var btn = document.getElementById('resend-moota-' + idPenjualan);
    var originalHtml = btn ? btn.innerHTML : null;
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mengirim...';
    }

    $.ajax({
        url: '/api/resend-moota',
        method: 'POST',
        data: {
            id_penjualan: idPenjualan,
            account_id: accountId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            try {
                var res = typeof response === 'string' ? JSON.parse(response) : response;
            } catch (e) { var res = response; }
            alert('Berhasil resend ke Moota');
            location.reload();
        },
        error: function(xhr) {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
            try {
                const res = JSON.parse(xhr.responseText);
                alert('Error: ' + (res.message || 'Gagal resend ke Moota'));
            } catch (e) {
                alert('Error: Gagal resend ke Moota');
            }
        }
    });
}

// Load bank buttons directly on detail page (so user selects bank when opening detail)
function loadBanksForDetail(idPenjualan) {
    var container = document.getElementById('moota_accounts_detail');
    if (!container) return;
    container.innerHTML = '<p style="width:100%; text-align:center; color:#666">Memuat daftar bank...</p>';
    fetch('/api/moota/accounts')
        .then(res => { if (!res.ok) throw new Error('HTTP ' + res.status); return res.json(); })
        .then(data => {
            if (data.error) { container.innerHTML = '<p style="width:100%; color:#c62828">Error: ' + (data.message || 'Gagal mengambil akun') + '</p>'; return; }

            var accounts = data.accounts || data.data || [];
            container.innerHTML = '';
            container.classList.add('moota-bank-list');
            if (!accounts || accounts.length === 0) { container.innerHTML = '<p style="width:100%; text-align:center; color:#666">Tidak ada akun bank tersedia</p>'; return; }

            var savedAccount = {!! json_encode($dt_header->moota_bank_account_id ?? '') !!};

            accounts.forEach(function(a){
                var id = a.id ?? a.account_id ?? a.accountId ?? a.code ?? a['account_id'] ?? a['id'];
                var name = a.name ?? a.bank_name ?? a.account_name ?? 'Bank';
                var icon = a.icon ?? '';
                var accountNum = a.account_number ?? '';

                var btn = document.createElement('button');
                btn.type = 'button'; btn.className = 'moota-bank-btn'; btn.id = 'detail-bank-btn-' + id;

                var parts = [];
                parts.push(icon ? '<img src="'+icon+'" alt="'+name+'">' : '<i class="fas fa-university" style="font-size:28px;color:#6b7280"></i>');
                parts.push('<div class="bank-name">'+name+'</div>');
                if (accountNum) parts.push('<div class="account-num">'+accountNum+'</div>');
                btn.innerHTML = parts.join('');

                btn.onclick = function(e){ e.preventDefault(); selectDetailBank(id, name, this, idPenjualan); };
                container.appendChild(btn);

                if (savedAccount && String(savedAccount) === String(id)) { setTimeout(function(){ selectDetailBank(id, name, btn, idPenjualan); }, 50); }
            });
        })
        .catch(err => { console.error('[loadBanksForDetail] Fetch error:', err); container.innerHTML = '<p style="width:100%; color:#c62828">Error: Gagal mengambil akun</p>'; });
}

function selectDetailBank(bankId, bankName, btnElement, idPenjualan) {
    // reuse global selected var
    resend_selected_bank_id = bankId;
    resend_selected_bank_name = bankName;

    var container = document.getElementById('moota_accounts_detail');
    if (container) {
        Array.from(container.children).forEach(function(btn){
            btn.classList && btn.classList.remove('selected');
        });
    }
    if (btnElement) {
        btnElement.classList.add('selected');
    }

    // Persist selection to server before enabling resend
    var btn = document.getElementById('resend-moota-' + idPenjualan);
    fetch('/transaksi/save-moota-bank', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ id_penjualan: idPenjualan, account_id: bankId, account_name: bankName })
    })
    .then(res => res.json())
    .then(data => {
        if (data && (data.ok || data.success)) {
            if (btn) btn.disabled = false;
        } else {
            alert('Gagal menyimpan pilihan bank');
            if (btn) btn.disabled = true;
        }
    })
    .catch(err => {
        console.error('[selectDetailBank] Save bank error:', err);
        alert('Gagal menyimpan pilihan bank');
        if (btn) btn.disabled = true;
    });
}

// Auto-load banks when opening detail if transaction not paid
document.addEventListener('DOMContentLoaded', function(){
    try {
        var mootaStatus = '{{ $dt_header->moota_status ?? '' }}';
        var idPenjualan = '{{ $dt_header->id_penjualan ?? '' }}';
        if (idPenjualan && mootaStatus !== 'success') {
            loadBanksForDetail(idPenjualan);
        }
    } catch(e){}
});
</script>

@endsection
