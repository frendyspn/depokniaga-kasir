@extends('layout.template')

@section('title', $title)

@section('content')
    <!-- App Capsule -->
    
    <div id="appCapsule" >

        <div class="row">

            <div class="col-12">
                <div class="form-group boxed ml-2 mr-2">
                    <div class="input-wrapper">
                        <label class="label" for="pos_barcode">Barcode</label>
                        <input type="text" class="form-control" id="pos_barcode" placeholder="{{__('bahasa.scan_barcode_disini')}}" onchange="scan_barcode($(this).val())">
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 col-sm-12" style="background:white">
                
                <!-- <button onclick="viewKeranjang()">cek</button> -->
                <div id="keranjang_view"></div>
            </div>

            <div class="col-md-8 col-sm-12">
                <div class="section mt-2">
                    <!-- <div class="splide__track"> -->
                        <div class="row">
                            <div class="col-12 row">
                                <input type="text" class="form-control col-6" placeholder="{{__('bahasa.cari_nama_barang')}}" name="cari_barang" id="cari_barang" onkeyup="cari_barang($(this).val())">
                                <input type="text" class="form-control col-6" placeholder="{{__('bahasa.cari_barcode')}}" name="cari_barcode" id="cari_barcode" onkeyup="cari_barcode($(this).val())">
                            </div>
                            <div class="col-12 row" id="kategori_place">
                            <select class="form-control" onchange="viewBarang($(this).val())">
                            <option value="">--{{__('bahasa.semua_kategori')}}--</option>
                            @for($i=0; $i < count($list_kategori); $i++)
                            <option value="{{$list_kategori[$i]->id_kategori_produk}}">{{$list_kategori[$i]->nama_kategori}}</option>
                            @endfor
                            </select>
                            </div>

                            <div class="col-12 mt-2 row" id="barang_place">
                            
                            </div>
                            
                        </div>
                    <!-- </div> -->
                </div>
            </div>
            
        </div>
        
        

    </div>


        
    
    <!-- * App Capsule -->

    <script>
        $( document ).ready(function() {
            viewBarang('')
            viewKeranjang()
        });

        function viewBarang(kategori) {
            $.ajax({
                url: "<?= route('pos_list_barang') ?>",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {kategori},
                dataType: 'HTML',
                success: function(response) {
                    $('#barang_place').html(response)
                },
                error: function(error) {
                    console.log("error" + error.Message);
                    notif('bg-danger', "{{__('bahasa.notif_barang_tidak_ditemukan')}}")
                }
            });
        }

        function cari_barang(nama_barang) {
            $.ajax({
                url: "<?= route('pos_list_barang') ?>",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {nama_barang},
                dataType: 'HTML',
                success: function(response) {
                    $('#barang_place').html(response)
                },
                error: function(error) {
                    console.log("error" + error.Message);
                    notif('bg-danger', "{{__('bahasa.notif_barang_tidak_ditemukan')}}")
                }
            });
        }

        function cari_barcode(barcode) {
            $.ajax({
                url: "<?= route('pos_list_barang') ?>",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {barcode},
                dataType: 'HTML',
                success: function(response) {
                    $('#barang_place').html(response)
                },
                error: function(error) {
                    console.log("error" + error.Message);
                    notif('bg-danger', "{{__('bahasa.notif_barang_tidak_ditemukan')}}")
                }
            });
        }

        function tambahKeranjang(id_barang){
            if($('#tempat_nama_konsumen').html() === ''){
                notif('bg-danger', "Konsumen Harus Diisi")
            } else {
                $.ajax({
                    url: "<?= route('pos_add_keranjang') ?>",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {id_barang},
                    dataType: 'JSON',
                    success: function(response) {
                        console.log(response)
                        viewKeranjang()
                    },
                    error: function(xhr) {
                        var msg = "{{__('bahasa.notif_barang_tidak_ditemukan')}}";
                        try {
                            var res = JSON.parse(xhr.responseText);
                            if (res.Message) msg = res.Message;
                        } catch(e) {}
                        notif('bg-danger', msg);
                    }
                });
            }
            
        }

        function viewKeranjang(){
            $.ajax({
                url: "<?= route('pos_view_keranjang') ?>",
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'HTML',
                success: function(response) {
                    $('#keranjang_view').html(response)
                    console.log('keranjang generated')
                },
                error: function(error) {
                    console.log("error" + error);
                }
            });
        }

        function cek_konsumen(no_hp) {
            $.ajax({
                url: "<?= route('pos_cek_konsumen') ?>",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {no_hp},
                dataType: 'TEXT',
                success: function(response) {
                    $('#tempat_nama_konsumen').html(response)
                },
                error: function(error) {
                    console.log("error" + error);
                }
            });
        }

        function cek_kupon(kode_voucher) {
            console.log(kode_voucher)
            loaderShow()
            $.ajax({
                url: "<?= route('pos_cek_kupon') ?>",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {kode_voucher},
                dataType: 'JSON',
                success: function(response) {
                    loaderHide()
                    viewKeranjang()
                },
                error: function(response) {
                    loaderHide()
                    notif('bg-danger', response.responseJSON)
                }
            });
        }

        function scan_barcode(barcode) {
            loaderShow()
            console.log(barcode)
            $.ajax({
                url: "<?= route('pos_scan_barcode') ?>",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {barcode},
                dataType: 'JSON',
                success: function(response) {
                    // $('#tempat_nama_konsumen').html(response)
                    console.log(response)
                    tambahKeranjang(response)
                    $('#pos_barcode').val('')
                    loaderHide()
                },
                error: function(error) {
                    loaderHide()
                    notif('bg-danger', "{{__('bahasa.notif_barang_tidak_ditemukan')}}")
                }
            });
        }
    </script>

@endsection
