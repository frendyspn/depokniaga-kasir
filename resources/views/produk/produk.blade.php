@extends('layout.template')

@section('title', $title)

@section('content')
    <!-- App Capsule -->
    <div id="appCapsule" class="full-height">

        <div class="section mt-2">

            <div class="row">
                <div class="col">
                    <select name="kategori_produk" id="kategori_produk" class="form-control" onchange="getProduk()">
                    <option value="">--Filter Kategori--</option>
                    @foreach($list_kategori as $row)
                    <option value="{{$row->id_kategori_produk}}">{{$row->nama_kategori}}</option>
                    @endforeach
                    </select>
                </div>
                
            </div>
            
        </div>

        <div class="section mt-2">
            <a href="{{route('add_produk')}}" class="btn btn-primary">Tambah Produk</a>
            <div class="card">

                <div class="table-responsive">
                    <table class="table table-striped" id="table_produk">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Barcode</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Kategori Produk</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    
    <!-- * App Capsule -->

    <script>

    $( document ).ready(function() {
        getProduk()
    });


    function getProduk() {
        
        $.ajax({
            url: "{{route('search_produk')}}",
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
                showData(response)
                
            },
            error: function(err) {
                loaderHide()
                // console.log(err);
                // $('#content-order').html(err)
            }    
        });
        // setInterval(loaderHide(), 50000);
    }

    function showData(data) {
        console.log(data)
        var tbl = ''
        for (let index = 0; index < data.length; index++) {
            var status = ''
            if(data[index]['aktif'] === 'Y'){
                status = 'Publish'
            }else if(data[index]['aktif'] === 'N'){
                status = 'Tolak'
            }else if(data[index]['aktif'] === 'R'){
                status = 'Revisi'
            }else if(data[index]['aktif'] === 'D'){
                status = 'Draft'
            }
            tbl += `
            <tr>
                <td>`+(index+1)+`</td>
                <td>`+data[index]['sku']+`</td>
                <td>`+data[index]['nama_produk']+`</td>
                <td>`+data[index]['nama_kategori']+`</td>
                <td class="text-end">`+formatMoney(data[index]['harga_konsumen'], 0, ".", ",")+`</td>
                <td>`+status+`</td>
                <td class="text-end"><a href="edit_produk/`+data[index]['id_produk']+`" class="btn btn-sm btn-danger"><ion-icon name="pencil-outline"></ion-icon></a></td>
            <tr>
            `
        }
        
        $('#table_produk tbody').html(tbl)
        
    }

    </script>

@endsection
