@extends('layout.template')

@section('title', $title)

@section('content')
    <!-- App Capsule -->
    <div id="appCapsule" class="full-height">

        <div class="section mt-2">
            <form class="row" method="post" action="{{route('save_produk')}}" enctype="multipart/form-data" id="form_add_produk" name="form_add_produk">
                @csrf
            <div class="col-md-6 col-sm-12">
                <div class="section-title">Detail Produk</div>
                <div class="card">
                    <div class="card-body">
                    
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="barcode">Barcode</label>
                                <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Barcode">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="nama_barang">Nama Barang</label>
                                <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Nama Barang">
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
                                    <option value="{{$row->id_kategori_produk}}">{{$row->nama_kategori}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="sub_kategori_produk">Sub Kategori</label>
                                <select class="form-control custom-select" id="sub_kategori_produk" name="sub_kategori_produk">
                                    <option value="">--Pilih SubKategori--</option>
                                    
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
                                <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Satuan">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="berat">Berat (gram)</label>
                                <input type="number" class="form-control" id="satuan" name="berat" placeholder="berat">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="harga_beli">Harga Beli</label>
                                <input type="number" class="form-control" id="harga_beli" name="harga_beli">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-wrapper">
                                        <label class="label" for="harga_jual">Harga Jual</label>
                                        <input type="number" class="form-control" id="harga_jual" name="harga_jual">
                                        <i class="clear-input">
                                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                     <div class="input-wrapper">
                                        <label class="label" for="harga_konsumen_minimal_order">Minimal Beli</label>
                                        <input type="number" class="form-control" id="harga_konsumen_minimal_order" name="harga_konsumen_minimal_order">
                                        <i class="clear-input">
                                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="form-group boxed d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-wrapper">
                                        <label class="label" for="harga_reseller">Harga Reseller</label>
                                        <input type="number" class="form-control" id="harga_reseller" name="harga_reseller">
                                        <i class="clear-input">
                                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                     <div class="input-wrapper">
                                        <label class="label" for="harga_reseller_minimal_order">Minimal Beli</label>
                                        <input type="number" class="form-control" id="harga_reseller_minimal_order" name="harga_reseller_minimal_order">
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
                                <input type="number" class="form-control" id="harga_premium" name="harga_premium">
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        
                        <div class="form-group boxed d-none">
                            <div class="input-wrapper">
                                <label class="label" for="harga_platform">Harga Platform (%)</label>
                                <input type="number" class="form-control" id="harga_platform" name="harga_platform" >
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        
                        <div class="form-group boxed d-none">
                            <div class="input-wrapper">
                                <label class="label" for="harga_level_newbie">Harga Level Newbie</label>
                                <input type="number" class="form-control" id="harga_level_newbie" name="harga_level_newbie" >
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        
                        <div class="form-group boxed d-none">
                            <div class="input-wrapper">
                                <label class="label" for="harga_level_pedagang">Harga Level Bronze</label>
                                <input type="number" class="form-control" id="harga_level_pedagang" name="harga_level_pedagang" >
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        
                        <div class="form-group boxed d-none">
                            <div class="input-wrapper">
                                <label class="label" for="harga_level_juragan">Harga Level Silver</label>
                                <input type="number" class="form-control" id="harga_level_juragan" name="harga_level_juragan" >
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        
                        <div class="form-group boxed d-none">
                            <div class="input-wrapper">
                                <label class="label" for="harga_level_big">Harga Level Gold</label>
                                <input type="number" class="form-control" id="harga_level_big" name="harga_level_big" >
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        
                        <div class="form-group boxed d-none">
                            <div class="input-wrapper">
                                <label class="label" for="harga_level_bos">Harga Level Platinum</label>
                                <input type="number" class="form-control" id="harga_level_bos" name="harga_level_bos" >
                                <i class="clear-input">
                                    <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="section-title">Deskripsi Produk</div>
                <div class="card">
                    <div class="card-body">
                    
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <textarea id="deskripsi_produk" name="deskripsi_produk" rows="2" class="form-control" placeholder="Deskripsi Produk"></textarea>
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
                    
                        <span class="btn btn-sm btn-primary" onclick="tambahVariasi()">Tambah Variasi</span>
                        <div class="table-responsive">
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
                            <div class="row">
                                <div class="custom-file-upload col-md-4 col-sm-6 col-xl-3" id="fileUpload1">
                                    <input type="file" id="fileuploadInput1" name="fileuploadInput1" accept=".png, .jpg, .jpeg">
                                    <label for="fileuploadInput1">
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
                // loaderShow()
            },
            success: function(response) {
                var $select = $('#sub_kategori_produk');

                $select.find('option').remove();
                $select.append('<option value="0"> --Pilih SubKategori-- </option>'); // return empty
                $.each(response,function(key, value)
                {
                    $select.append('<option value=' + value['id_kategori_produk_sub'] + '>' + value['nama_kategori_sub'] + '</option>'); // return empty
                });
                // loaderHide()
            },
            error: function(err) {
                // loaderHide()
            }    
        });
    }


    function tambahVariasi() {
        var $tbl = $('#table_variasi tbody');
        var ind = $('#table_variasi tbody .form-variasi').length + 1;

        $tbl.append(`
        <tr class="form-variasi">
            <td><input style="width:200px" type="text" class="form-control" id="type_variasi`+ind+`" name="type_variasi[]"></td>
            <td><input style="width:200px" type="text" class="form-control" id="variasi`+ind+`" name="variasi[]"></td>
            <td><input style="width:200px" type="text" class="form-control" id="penambah_harga_variasi`+ind+`" name="penambah_harga_variasi[]"></td>
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
            url: "{{route('save_produk')}}",
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
                notif('bg-success', response.Message)
                loaderHide()
                history.back()
            },
            error: function(err) {
                notif('bg-danger', err.responseJSON.Message)
                
                loaderHide()
            }    
        });

        
    }
    

    </script>

@endsection
