<?php
$total_belanja = 0;
?>

<style>
@media print {
   .print-hide{
    display:none
   }
}
</style>

<div style="padding-left:15px">
    <div class="form-group basic">
        <div class="input-wrapper">
            <label class="label" for="pos_konsumen">{{__('bahasa.Nomor_HP')}} {{__('bahasa.konsumen')}}</label>
            <input type="number" class="form-control" id="pos_konsumen" name="pos_konsumen" onchange="cek_konsumen($(this).val())" value="{{$POS['no_konsumen']}}" >
            <div id="tempat_nama_konsumen">{{$POS['nama_konsumen']}}</div>
        </div>
    </div>
</div>

<div class="section mt-2 mb-2" style="background:white">
    <div class="listed-detail mt-3">
        <h3 class="text-center mt-2">{{__('bahasa.daftar_belanja')}}</h3>
    </div>

    <div class="listview flush transparent no-space mt-3">
        @for($i=0; $i < count($dtKeranjang); $i++)
        @php 
        if($POS['tipe_konsumen'] == 'umum'){
            $harga = $dtKeranjang[$i]['harga_konsumen'];     
        } else {
            $harga = $dtKeranjang[$i]['harga_konsumen']; 
        }
        $harga = $harga - $dtKeranjang[$i]['diskon'];
        @endphp
        <small style="margin-top:5px">{{$dtKeranjang[$i]['nama_produk']}} <ion-icon class="bg-primary" style="padding:2px;" name="pencil" onclick="edit_barang({{$dtKeranjang[$i]['id_produk']}})"></ion-icon></small>
        
        <li style="line-height:0.25rem; margin-bottom:10px">
            <small>
                {{$dtKeranjang[$i]['qty']}} x {{number_format($harga)}}
                @if($dtKeranjang[$i]['diskon'] > 0)
                <br/><br/><br/><span class="text-danger">{{__('bahasa.hemat')}}. {{number_format($dtKeranjang[$i]['diskon'] * $dtKeranjang[$i]['qty'])}}</span>
                @endif
            </small>
            
            
            <small>
                {{number_format($harga * $dtKeranjang[$i]['qty'])}}
                @if($dtKeranjang[$i]['diskon'] > 0)
                <br/><br/><br/><br/><br/><br/>
                @endif
            </small>

        </li>
        
        @php $total_belanja = $total_belanja + ($harga * $dtKeranjang[$i]['qty']) @endphp
        @endfor
</div>


    <ul class="listview flush transparent simple-listview no-space mt-3">
        <li>
            <strong>{{__('bahasa.sub_total')}}</strong>
            <span>{{number_format($total_belanja)}}</span>
        </li>
        <li>
            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="select4">{{__('bahasa.pengirim')}}</label>
                    <select class="form-control custom-select" id="pos_pengiriman" name="pos_pengiriman" onchange="pilih_pengiriman($(this).val())">
                        <option <?php if($POS['pengiriman']['kurir'] == 'tanpa_ongkir') {echo 'selected';} ?> value="tanpa_ongkir">{{__('bahasa.tanpa_ongkir')}}</option>
                        <option <?php if($POS['pengiriman']['kurir'] == 'ongkir_toko') {echo 'selected';} ?> value="ongkir_toko">{{__('bahasa.ongkir_toko')}}</option>
                    </select>
                </div>
            </div>
        </li>
        {{-- input kordinat selalu ada di DOM agar bisa dibaca JS, tampil/sembunyi lewat CSS --}}
        <input type="hidden" id="pos_kordinat_pengiriman" value="{{ $POS['pengiriman']['kordinat_konsumen'] ?? '' }}">

        @if(!empty($POS['nama_konsumen']))
        <li>
            <div style="width:100%">
                <div class="form-group basic" style="margin-bottom:6px">
                    <label class="label">Alamat Pengiriman</label>
                    <textarea class="form-control" id="pos_alamat_pengiriman" rows="3"
                        placeholder="Masukkan alamat tujuan pengiriman...">{{ $POS['pengiriman']['alamat_antar'] ?? '' }}</textarea>
                </div>
                <div class="form-group basic" style="margin-bottom:6px">
                    <label class="label">Koordinat Pengiriman</label>
                    <input type="text" class="form-control" id="pos_kordinat_pengiriman_display"
                        placeholder="-6.123456,106.123456"
                        value="{{ $POS['pengiriman']['kordinat_konsumen'] ?? '' }}"
                        oninput="window.posKordinat = this.value">
                </div>
                <div class="form-group basic" style="margin-bottom:6px">
                    <label class="label">Link Google Maps</label>
                    <div style="display:flex;gap:6px;align-items:center">
                        <input type="text" class="form-control" id="pos_link_maps" placeholder="Tempel link Google Maps di sini...">
                        <button class="btn btn-sm btn-secondary" style="white-space:nowrap" onclick="ekstrak_kordinat()">Ambil</button>
                    </div>
                </div>
                <button class="btn btn-sm btn-outline-primary btn-block" onclick="simpan_kordinat()">Simpan Alamat &amp; Koordinat</button>
            </div>
        </li>
        @endif
        <li>
            <strong>{{__('bahasa.biaya_kirim')}}</strong>
            <span id="ongkir-tampil">{{number_format($POS['pengiriman']['ongkir'])}}</span>
        </li>
        <li id="li_info_jarak" style="display:none">
            <small id="info_jarak" style="color:#1565c0"></small>
        </li>
        <li>
            <strong>{{__('bahasa.diskon')}}</strong>
            <span style="margin-left:20px">{{number_format($POS['diskon'])}}</span>
        </li>
        <li>
            <strong>{{__('bahasa.total_belanja')}}</strong>
            <span>{{number_format($total_belanja + $POS['pengiriman']['ongkir'] - $POS['diskon'])}}</span>
        </li>
        
        <li>
            <strong>{{__('bahasa.pembayaran')}}</strong>
            <span style="margin-left:20px"><input type="number" class="form-control" style="text-align:right" onkeyup="hitung_kembalian($(this).val())" onchange="kembalian_submit($(this).val())" value="{{ $POS['bayar'] }}"></span>
        </li>
        <li>
            <strong>{{__('bahasa.kembalian')}}</strong>
            <span id="kembalian-tampil" style="font-weight:bold">{{number_format( $POS['bayar'] - ($total_belanja + $POS['pengiriman']['ongkir'] - $POS['diskon']) )}}</span>
        </li>

        <li>
            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="pos_kupon">{{__('bahasa.kupon')}}</label>
                    <input type="text" class="form-control" id="pos_kupon" name="pos_kupon" value="{{$POS['voucher']['kode_kupon']}}" >
                    <button class="btn btn-sm btn-secondary" onclick="cek_kupon($('#pos_kupon').val())">{{__('bahasa.cek_kupon')}}</button>
                    <div id="tempat_kupon">{{$POS['voucher']['keterangan']}}</div>
                </div>
            </div>
        </li>

        <li>
            <button class="btn btn-primary btn-block print-hide" onclick="bayar()">{{strtoupper(__('bahasa.pembayaran'))}}</button>
        </li>
    </ul>
</div>


        <div class="modal fade dialogbox" id="ModalUpdateBarangKeranjang" data-bs-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('bahasa.update_barang_pesanan')}}</h5>
                    </div>
                    <form>
                        <div class="modal-body text-start mb-2">
                            <form id="form_update_pesanan" >
                                <div id="tempat_update_pesanan"></div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-inline">
                                <button type="button" class="btn btn-text-secondary"
                                    data-bs-dismiss="modal">{{strtoupper(__('bahasa.batal'))}}</button>
                                <button type="button" class="btn btn-text-primary" onclick="update_pesanan()"
                                    data-bs-dismiss="modal">{{strtoupper(__('bahasa.update'))}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        


<script>
    // Sinkronkan window.posKordinat dengan session value, tapi jangan override jika sudah ada nilai
    // Prioritas: window.posKordinat > input display value > session value
    (function(){
        var displayInput = document.getElementById('pos_kordinat_pengiriman_display');
        var sessionValue = '{{ $POS['pengiriman']['kordinat_konsumen'] }}';
        
        // Jika window.posKordinat belum punya nilai, gunakan dari session atau input
        if (!window.posKordinat || window.posKordinat === '') {
            if (displayInput && displayInput.value.trim()) {
                window.posKordinat = displayInput.value.trim();
            } else if (sessionValue) {
                window.posKordinat = sessionValue;
            }
        }
        
        // Sinkronkan input display agar selalu sesuai dengan window.posKordinat
        if (displayInput && window.posKordinat && !displayInput.value.trim()) {
            displayInput.value = window.posKordinat;
        }
    })();

    function tampilInfoJarak(response) {
        if (response && response.jarak_km !== null && response.jarak_km !== undefined) {
            var via = response.via === 'google' ? 'Google Maps' : 'estimasi garis lurus';
            $('#info_jarak').html('📍 Jarak: <strong>' + response.jarak_km + ' km</strong> (via ' + via + ') &nbsp;·&nbsp; Ongkir: <strong>Rp' + response.ongkir.toLocaleString('id-ID') + '</strong>');
            $('#li_info_jarak').show();
        } else {
            $('#li_info_jarak').hide();
        }
    }

    function pilih_pengiriman(pengiriman){
        var display  = document.getElementById('pos_kordinat_pengiriman_display');
        var kordinat = display ? display.value.trim() : (window.posKordinat || '').trim();
        
        // Validasi: jika memilih ongkir_toko dan koordinat kosong, minta input terlebih dahulu
        if (pengiriman === 'ongkir_toko' && !kordinat) {
            notif('bg-warning', 'Mohon masukkan koordinat pengiriman terlebih dahulu');
            return;
        }
        
        $.ajax({
            url: "<?= route('pos_pilih_pengiriman') ?>",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'JSON',
            data : {pengiriman: pengiriman, kordinat_pengiriman: kordinat},
            success: function(response) {
                tampilInfoJarak(response);
                viewKeranjang()
            },
            error: function(xhr) {
                var msg = xhr.responseText || 'Terjadi kesalahan';
                notif('bg-danger', msg);
                viewKeranjang()
            }
        });
    }

    function input_diskon(amount) {
        $.ajax({
            url: "<?= route('pos_input_diskon') ?>",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'HTML',
            data : {amount},
            success: function(response) {
                viewKeranjang()
            },
            error: function(error) {
                console.log("error" + error);
            }
        });
    }

    function kembalian_submit(bayar) {
        $.ajax({
            url: "<?= route('pos_input_bayar') ?>",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'HTML',
            data : {bayar},
            success: function(response) {
                viewKeranjang()
            },
            error: function(error) {
                console.log("error" + error);
            }
        });
    }

    function hitung_kembalian(bayar) {
        var total_belanja = "<?= $total_belanja + $POS['pengiriman']['ongkir'] - $POS['diskon'] ?>"
        var kembali = bayar-total_belanja
        $('#kembalian-tampil').html(kembali)
        if (kembali < 0) {
            $('#kembalian-tampil').removeClass('text-success')
            $('#kembalian-tampil').addClass('text-danger')
        } else {
            $('#kembalian-tampil').removeClass('text-danger')
            $('#kembalian-tampil').addClass('text-success')
        }
    }

    function edit_barang(id_barang) {
        $.ajax({
            url: "<?= route('pos_edit_barang_keranjang') ?>",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'HTML',
            data : {id_barang},
            success: function(response) {
                $('#ModalUpdateBarangKeranjang').modal('show')
                $('#tempat_update_pesanan').html(response)
            },
            error: function(error) {
                console.log("error" + error);
            }
        });
    }

    function update_pesanan() {
        $.ajax({
            url: "<?= route('pos_update_barang_keranjang') ?>",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'TEXT',
            data : $("form").serialize(),
            success: function(response) {
                viewKeranjang()
            },
            error: function(error) {
                console.log("error" + error);
            }
        });
    }

    function simpan_kordinat() {
        var alamat   = $('#pos_alamat_pengiriman').val().trim();
        var display  = document.getElementById('pos_kordinat_pengiriman_display');
        var kordinat = display ? display.value.trim() : (window.posKordinat || '').trim();
        $.ajax({
            url: "<?= route('pos_simpan_kordinat') ?>",
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { alamat_pengiriman: alamat, kordinat_pengiriman: kordinat },
            dataType: 'JSON',
            success: function(response) {
                notif('bg-success', 'Alamat & Koordinat tersimpan');
                tampilInfoJarak(response);
                viewKeranjang();
            },
            error: function() {
                notif('bg-danger', 'Gagal menyimpan alamat');
            }
        });
    }

    function ekstrak_kordinat() {
        var url = $('#pos_link_maps').val().trim();
        var match = url.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/)
                 || url.match(/[?&]q=(-?\d+\.\d+),(-?\d+\.\d+)/)
                 || url.match(/ll=(-?\d+\.\d+),(-?\d+\.\d+)/);
        if (match) {
            var kord = match[1] + ',' + match[2];
            window.posKordinat = kord;
            var display = document.getElementById('pos_kordinat_pengiriman_display');
            if (display) display.value = kord;
            $('#pos_link_maps').val('');
        } else {
            notif('bg-danger', 'Format link Maps tidak dikenali');
        }
    }

    function bayar() {
        var kurir = $('#pos_pengiriman').val();
        if (kurir === 'ongkir_toko') {
            var alamat = $('#pos_alamat_pengiriman').val().trim();
            if (!alamat) {
                notif('bg-danger', 'Alamat tujuan pengiriman tidak boleh kosong');
                return;
            }
        }

        $('#DialogLoading').modal('show')
        $.ajax({
            url: "<?= route('pos_bayar') ?>",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                alamat_pengiriman:   ($('#pos_alamat_pengiriman').val()   || '').trim(),
                kordinat_pengiriman: (function(){ var d = document.getElementById('pos_kordinat_pengiriman_display'); return d ? d.value.trim() : (window.posKordinat||''); })()
            },
            dataType: 'JSON',
            success: function(response) {
                console.log(response)
                // return
                $('#DialogLoading').modal('hide')

                var printContents = document.getElementById('keranjang_view').innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = "<html><head><title></title></head><body>" + printContents + "</body>";
                window.print();
                document.body.innerHTML = originalContents;
                window.location.replace("<?= route('pos_finish') ?>");
            },
            error: function(response) {
                notif('bg-danger', response.responseJSON)

                $('#DialogLoading').modal('hide')
            }
        });
    }

</script>