@extends('layout.template')

@section('title', $title)

@section('content')
    <!-- App Capsule -->
    <div id="appCapsule" class="full-height">
        <!--{{json_encode($dt_produk_varias)}}-->
        <div class="section mt-2">
            <form class="row" method="post" action="{{route('update_produk')}}" enctype="multipart/form-data" id="form_add_produk" name="form_add_produk">
                @csrf
            <div class="col-md-6 col-sm-12">
                <div class="section-title">Detail Produk</div>
                <div class="card">
                    <div class="card-body">
                    
                    <input type="text" class="form-control" id="id_barang" name="id_barang" value="{{$dt_produk->id_produk}}">

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="barcode">Barcode</label>
                                <input type="text" class="form-control" id="barcode" name="barcode" value="{{$dt_produk->sku}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="nama_barang">Nama Barang</label>
                                <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="{{$dt_produk->nama_produk}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="kategori_produk">Kategori</label>
                                <select class="form-control custom-select" id="kategori_produk" name="kategori_produk" onchange="getSubKategori()">
                                    <option value="">--Pilih Kategori--</option>
                                    @foreach($list_kategori as $row)
                                    <option value="{{$row->id_kategori_produk}}" @if($row->id_kategori_produk == $dt_produk->id_kategori_produk)? selected @endif>{{$row->nama_kategori}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="sub_kategori_produk">Sub Kategori</label>
                                <select class="form-control custom-select" id="sub_kategori_produk" name="sub_kategori_produk">
                                    <option value="0">--Pilih SubKategori--</option>
                                    @foreach($list_kategori_sub as $row)
                                    <option value="{{$row->id_kategori_produk_sub}}" @if($row->id_kategori_produk_sub == $dt_produk->id_kategori_produk_sub)? selected @endif>{{$row->nama_kategori_sub}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="section-title">Harga Produk</div>
                <div class="card">
                    <div class="card-body">
                    
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="satuan">Satuan</label>
                                <input type="text" class="form-control" id="satuan" name="satuan" value="{{$dt_produk->satuan}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="berat">Berat (gram)</label>
                                <input type="number" class="form-control" id="satuan" name="berat" value="{{$dt_produk->berat}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="harga_beli">Harga Beli</label>
                                <input type="number" class="form-control" id="harga_beli" name="harga_beli" value="{{$dt_produk->harga_beli}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-wrapper">
                                        <label class="label" for="harga_jual">Harga Ecer</label>
                                        <input type="number" class="form-control" id="harga_jual" name="harga_jual" value="{{$dt_produk->harga_konsumen}}">
                                        <i class="clear-input">
                                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                     <div class="input-wrapper">
                                        <label class="label" for="harga_konsumen_minimal_order">Minimal Beli</label>
                                        <input type="number" class="form-control" id="harga_konsumen_minimal_order" name="harga_konsumen_minimal_order" value="{{$dt_produk->harga_konsumen_minimal_order}}">
                                        <i class="clear-input">
                                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="form-group boxed">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-wrapper">
                                        <label class="label" for="harga_reseller">Harga Reseller</label>
                                        <input type="number" class="form-control" id="harga_reseller" name="harga_reseller" value="{{$dt_produk->harga_reseller}}">
                                        <i class="clear-input">
                                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                     <div class="input-wrapper">
                                        <label class="label" for="harga_reseller_minimal_order">Minimal Beli</label>
                                        <input type="number" class="form-control" id="harga_reseller_minimal_order" name="harga_reseller_minimal_order" value="{{$dt_produk->harga_reseller_minimal_order}}">
                                        <i class="clear-input">
                                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group boxed d-none">
                            <div class="input-wrapper">
                                <label class="label" for="harga_premium">Harga Premium</label>
                                <input type="number" class="form-control" id="harga_premium" name="harga_premium" value="{{$dt_produk->harga_premium}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        
                        <div class="form-group boxed d-none">
                            <div class="input-wrapper">
                                <label class="label" for="harga_platform">Harga Platform (%)</label>
                                <input type="number" class="form-control" id="harga_platform" name="harga_platform" value="{{$dt_produk->harga_platform_persen}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        
                        <div class="form-group boxed d-none">
                            <div class="input-wrapper">
                                <label class="label" for="harga_level_newbie">Harga Level Newbie</label>
                                <input type="number" class="form-control" id="harga_level_newbie" name="harga_level_newbie" value="{{$dt_produk->harga_level_newbie}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        
                        <div class="form-group boxed d-none">
                            <div class="input-wrapper">
                                <label class="label" for="harga_level_pedagang">Harga Level Bronze</label>
                                <input type="number" class="form-control" id="harga_level_pedagang" name="harga_level_pedagang" value="{{$dt_produk->harga_level_pedagang}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        
                        <div class="form-group boxed d-none">
                            <div class="input-wrapper">
                                <label class="label" for="harga_level_juragan">Harga Level Silver</label>
                                <input type="number" class="form-control" id="harga_level_juragan" name="harga_level_juragan" value="{{$dt_produk->harga_level_juragan}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        
                        <div class="form-group boxed d-none">
                            <div class="input-wrapper">
                                <label class="label" for="harga_level_big">Harga Level Gold</label>
                                <input type="number" class="form-control" id="harga_level_big" name="harga_level_big" value="{{$dt_produk->harga_level_big}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        
                        <div class="form-group boxed d-none">
                            <div class="input-wrapper">
                                <label class="label" for="harga_level_bos">Harga Level Platinum</label>
                                <input type="number" class="form-control" id="harga_level_bos" name="harga_level_bos" value="{{$dt_produk->harga_level_bos}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="section-title">Status Produk</div>
                <div class="card">
                    <div class="card-body">
                        @if($dt_produk->aktif == 'Y')
                        <span class="badge badge-primary">Publish</span>
                        @elseif($dt_produk->aktif == 'N')
                        <span class="badge badge-danger">Tolak</span>
                        @elseif($dt_produk->aktif == 'R')
                        <span class="badge badge-warning">Revisi</span>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <textarea id="catatan_revisi" name="catatan_revisi" rows="2" class="form-control" placeholder="Perlu Revisi" readonly>{{$dt_produk->revisi_feedback}}</textarea>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        @elseif($dt_produk->aktif == 'D')
                        <span class="badge badge-info">Draft</span>
                        @endif
                        

                    </div>
                </div>
            </div>
            
            
            <div class="col-md-12">
                <div class="section-title">Deskripsi Produk</div>
                <div class="card">
                    <div class="card-body">
                    
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <textarea id="deskripsi_produk" name="deskripsi_produk" rows="2" class="form-control" placeholder="Deskripsi Produk">{{$dt_produk->tentang_produk}}</textarea>
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            

            <div class="col-md-12 d-none">
                <div class="section-title">Variasi Produk</div>
                <div class="card">
                    <div class="card-body">
                    
                        <span class="btn btn-sm btn-primary" onclick="tambahVariasi('','','','','')">Tambah Variasi</span>
                        <div class="table-responsive" style="width: 100%;overflow-x: auto;white-space: nowrap;">
                            <table class="table" id="table_variasi">
                                <thead>
                                    <tr>
                                        <th scope="col">Type Variasi</th>
                                        <th scope="col">Variasi</th>
                                        <th scope="col">Harga Konsumen</th>
                                        <th scope="col" class="text-end">Aksi</th>
                                    </tr>
                                    <tr>
                                        <td colspan="10"><small>Contoh Pengisian, pisahkan variasi dengan ; (titik koma)</small></td>
                                    </tr>
                                    <tr>
                                        <td><small>Warna</small></td>
                                        <td><small>Hijau;Merah</small></td>
                                        <td><small>500;500</small></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="section-title">Foto Produk</div>
                    <div class="card" >
                        <div class="card-body">
                            <input type="hidden" class="form-control" id="gambar" name="gambar" value="{{$dt_produk->gambar}}">

                            <div class="row">
                                <div class="custom-file-upload col-md-4 col-sm-6 col-xl-3" id="fileUpload1">
                                    <input type="file" id="fileuploadInput1" name="fileuploadInput1" accept=".png, .jpg, .jpeg">
                                    <label for="fileuploadInput1" id="lb_fileuploadInput1">
                                        <span>
                                            <strong>
                                                <ion-icon name="arrow-up-circle-outline" role="img" class="md hydrated" aria-label="arrow up circle outline"></ion-icon>
                                                <i>Upload a Photo</i>
                                            </strong>
                                        </span>
                                    </label>
                                </div>

                                <div class="custom-file-upload col-md-4 col-sm-6 col-xl-3" id="fileUpload2">
                                    <input type="file" id="fileuploadInput2" name="fileuploadInput2" accept=".png, .jpg, .jpeg">
                                    <label for="fileuploadInput2">
                                        <span>
                                            <strong>
                                                <ion-icon name="arrow-up-circle-outline" role="img" class="md hydrated" aria-label="arrow up circle outline"></ion-icon>
                                                <i>Upload a Photo</i>
                                            </strong>
                                        </span>
                                    </label>
                                </div>

                                <div class="custom-file-upload col-md-4 col-sm-6 col-xl-3" id="fileUpload3">
                                    <input type="file" id="fileuploadInput3" name="fileuploadInput3" accept=".png, .jpg, .jpeg">
                                    <label for="fileuploadInput3">
                                        <span>
                                            <strong>
                                                <ion-icon name="arrow-up-circle-outline" role="img" class="md hydrated" aria-label="arrow up circle outline"></ion-icon>
                                                <i>Upload a Photo</i>
                                            </strong>
                                        </span>
                                    </label>
                                </div>

                                <div class="custom-file-upload col-md-4 col-sm-6 col-xl-3" id="fileUpload4">
                                    <input type="file" id="fileuploadInput4" name="fileuploadInput4" accept=".png, .jpg, .jpeg">
                                    <label for="fileuploadInput4">
                                        <span>
                                            <strong>
                                                <ion-icon name="arrow-up-circle-outline" role="img" class="md hydrated" aria-label="arrow up circle outline"></ion-icon>
                                                <i>Upload a Photo</i>
                                            </strong>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <button type="button" onclick="saveProduk()" class="btn btn-primary btn-lg btn-block mt-2 mb-3">SIMPAN</button>
            </div>

            </form>
        </div>
    </div>
    
    <!-- * App Capsule -->

    <script>

    $( document ).ready(function() {
        var variasi = JSON.parse('<?=$dt_produk_varias?>')
        for (let index = 0; index < variasi.length; index++) {
            tambahVariasi(variasi[index].id_variasi, variasi[index].nama, variasi[index].variasi, variasi[index].variasi_harga, variasi[index].harga_platform)
        }

        var source = '<?=$dt_produk->source?>'
        
        var gambar = '<?=$dt_produk->gambar?>'.split(";")
        console.log(gambar)
        if (gambar[0]) {
            $('#lb_fileuploadInput1').addClass('file-uploaded')
            if (source === 'POS') {
                $('#lb_fileuploadInput1').css('background-image', "url('<?=env('ADMIN_URL')?>asset/foto_produk/"+gambar[0]+"')");
            } else {
                $('#lb_fileuploadInput1').style.backgroundImage = "url('blob:{{asset('assets/img/sample/avatar/avatar2.jpg')}}')"
            }
            $('#lb_fileuploadInput1').html('<span>Gambar 1</span>')
        }

        if (gambar[1]) {
            $('#lb_fileuploadInput2').addClass('file-uploaded')
            if (source === 'POS') {
                $('#lb_fileuploadInput2').css('background-image', "url('<?=env('ADMIN_URL')?>asset/foto_produk/"+gambar[1]+"')");
            } else {
                $('#lb_fileuploadInput2').style.backgroundImage = "url('blob:{{asset('assets/img/sample/avatar/avatar2.jpg')}}')"
            }
            $('#lb_fileuploadInput2').html('<span>Gambar 2</span>')
        }

        if (gambar[2]) {
            $('#lb_fileuploadInput3').addClass('file-uploaded')
            if (source === 'POS') {
                $('#lb_fileuploadInput3').css('background-image', "url('<?=env('ADMIN_URL')?>asset/foto_produk/"+gambar[2]+"')");
            } else {
                $('#lb_fileuploadInput3').style.backgroundImage = "url('blob:{{asset('assets/img/sample/avatar/avatar2.jpg')}}')"
            }
            $('#lb_fileuploadInput3').html('<span>Gambar 3</span>')
        }

        if (gambar[3]) {
            $('#lb_fileuploadInput4').addClass('file-uploaded')
            if (source === 'POS') {
                $('#lb_fileuploadInput4').css('background-image', "url('<?=env('ADMIN_URL')?>asset/foto_produk/"+gambar[3]+"')");
            } else {
                $('#lb_fileuploadInput4').style.backgroundImage = "url('blob:{{asset('assets/img/sample/avatar/avatar2.jpg')}}')"
            }
            $('#lb_fileuploadInput4').html('<span>Gambar 4</span>')
        }
    });

    function getSubKategori() {
        $.ajax({
            url: "{{route('get_sub_kategori')}}",
            type: "POST",   
            dataType:"JSON",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') 
            },
            data:{
                kategori : $('#kategori_produk').val(),
            },
            beforeSend: function () {
                loaderShow()
            },
            success: function(response) {
                var $select = $('#sub_kategori_produk');

                $select.find('option').remove();
                $select.append('<option value="0"> --Pilih SubKategori-- </option>'); // return empty
                $.each(response,function(key, value)
                {
                    $select.append('<option value=' + value['id_kategori_produk_sub'] + '>' + value['nama_kategori_sub'] + '</option>'); // return empty
                });
                loaderHide()
            },
            error: function(err) {
                loaderHide()
            }    
        });
    }


    function tambahVariasi(idVariasai, typeVariasi, variasi, hargaVariasi, hargaPlatform) {
        var $tbl = $('#table_variasi tbody');
        var ind = $('#table_variasi tbody .form-variasi').length + 1;

        $tbl.append(`
        <tr class="form-variasi">
            <td><input style="width:200px" type="hidden" class="form-control" id="id_variasi`+ind+`" name="id_variasi[]" value="`+idVariasai+`"> <input style="width:200px" type="text" class="form-control" id="type_variasi`+ind+`" name="type_variasi[]" value="`+typeVariasi+`"></td>
            <td><input style="width:200px" type="text" class="form-control" id="variasi`+ind+`" name="variasi[]" value="`+variasi+`"></td>
            <td><input style="width:200px" type="text" class="form-control" id="penambah_harga_variasi`+ind+`" name="penambah_harga_variasi[]" value="`+hargaVariasi+`"></td>
            <td><span class="btn btn-sm btn-danger" onclick="deleteRow(this)"><ion-icon name="trash-outline"></ion-icon></span></td>
        </tr>
        `);

    }

    function deleteRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

    function saveProduk() {
        

        $.ajax({
            url: "{{route('update_produk')}}",
            type: "POST",   
            dataType:"JSON",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') 
            },
            data:new FormData(document.getElementsByName('form_add_produk')[0]),
            processData: false,
            contentType: false,
            beforeSend: function () {
                // loaderShow()
            },
            success: function(response) {
                console.log(response)
                notif('bg-primary', response.Message)
                loaderHide()
            },
            error: function(err) {
                notif('bg-danger', err.responseJSON.Message)
                
                loaderHide()
            }    
        });

        
    }
    

    </script>

@endsection
