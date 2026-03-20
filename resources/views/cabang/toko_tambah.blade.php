@extends('layout.template')

@section('title', $title)

@section('content')
    <!-- App Capsule -->
    
    <div id="appCapsule" class="full-height">
        <!-- {{json_encode($detail_cabang)}} -->
        <form method="post" action="{{route('cabang_simpan')}}">
            @csrf
            <div class="section mt-2">
                <div class="section-title">{{__('bahasa.profil')}}</div>
                <div class="card">
                    <div class="card-body">
                    <input type="hidden" class="form-control" id="id_toko" name="id_toko"  value="<?php if($detail_cabang) {echo $detail_cabang->id_reseller;} else {echo'-';} ?>">
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="nama_toko">{{__('bahasa.nama_cabang')}}</label>
                                <input type="text" class="form-control" id="nama_toko" name="nama_toko" placeholder="{{__('bahasa.nama_cabang')}}" value="<?php if($detail_cabang) {echo $detail_cabang->nama_reseller;} else {echo'';} ?>" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="deskripsi_toko">{{__('bahasa.deskripsi_cabang')}}</label>
                                <textarea rows="2" class="form-control" id="deskripsi_toko" name="deskripsi_toko" placeholder="{{__('bahasa.deskripsi_cabang')}}"  required><?php if($detail_cabang) {echo $detail_cabang->keterangan;} else {echo'';} ?></textarea>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="nomor_hp_toko">{{__('bahasa.Nomor_HP')}}</label>
                                <input type="text" class="form-control" id="nomor_hp_toko" name="nomor_hp_toko" value="<?php if($detail_cabang) {echo $detail_cabang->no_telpon;} else {echo Session::get('no_hp');} ?>" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="jenis_toko">Jenis Toko</label>
                                <select class="form-control custom-select" id="jenis_toko" name="jenis_toko" required>
                                    <option value="non-resto">Non Resto</option>
                                    <option value="resto">Resto</option>
                                </select>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="section mt-2">
                <div class="section-title">{{__('bahasa.Alamat')}}</div>
                <div class="card">
                    <div class="card-body">

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="provinsi_toko">{{__('bahasa.Provinsi')}}</label>
                                <select class="form-control custom-select" id="provinsi_toko" name="provinsi_toko" onchange="get_city($(this).val())" required></select>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="kota_toko">{{__('bahasa.Kota')}}</label>
                                <select class="form-control custom-select" id="kota_toko" name="kota_toko" onchange="get_district($(this).val())" required></select>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="kecamatan_toko">{{__('bahasa.Kecamatan')}}</label>
                                <select class="form-control custom-select" id="kecamatan_toko" name="kecamatan_toko" required></select>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="alamat_toko">{{__('bahasa.Alamat')}}</label>
                                <textarea rows="2" class="form-control" id="alamat_toko" name="alamat_toko" placeholder="{{__('bahasa.Alamat')}}" required><?php if($detail_cabang) {echo $detail_cabang->alamat_lengkap;} else {echo'';} ?></textarea>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="kordinat_toko">Titik Kordinat</label>
                                <textarea rows="2" class="form-control" id="kordinat_toko" name="kordinat_toko" placeholder="Titik Kordinat" required><?php if($detail_cabang) {echo $detail_cabang->kordinat;} else {echo'';} ?></textarea>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                            <button type="button" class="btn btn-secondary btn-block mt-1" onclick="getCurrentLocation()">
                                <ion-icon name="locate"></ion-icon> Gunakan Lokasi Saat Ini
                            </button>
                        </div>
                        

                    </div>
                </div>
            </div>

            <div class="section mb-7 p-2">
                <div class="form-button transparent">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">{{__('bahasa.simpan')}}</button>
                    @if($detail_cabang)
                    <button type="button" class="btn btn-secondary btn-block btn-lg mt-5" onclick="notification('verif-delete')">{{__('bahasa.btn_title_hapus')}}</button>
                    @endif
                </div>
            </div>
            

        </form>
    </div>


        @if($detail_cabang)
        <div id="verif-delete" class="notification-box" tabindex="-1">
            <div class="notification-dialog ios-style bg-danger">
                <div class="notification-header">
                    <div class="in">
                        <strong>{{__('bahasa.notif_cabang_akan_dihapus')}}</strong>
                    </div>
                    <div class="right">
                        <a href="#" class="close-button">
                            <ion-icon name="close-circle"></ion-icon>
                        </a>
                    </div>
                </div>
                <div class="notification-content">
                    <div class="in">
                        <h3 class="subtitle">{{__('bahasa.notif_hapus_cabang')}}?</h3>
                        <div class="text">
                            {{__('bahasa.notif_ya_konfirmasi')}}
                        </div>
                    </div>
                </div>
                <div class="notification-footer">
                    <a href="{{url('cabang_hapus/'.$detail_cabang->id_reseller)}}"  class="notification-button" onclick="$('#DialogLoading').modal('show')">
                        {{__('bahasa.btn_title_yes')}}
                    </a>
                    <a href="#" class="notification-button close-button" data-dismiss="modal">
                        {{__('bahasa.batal')}}
                    </a>
                </div>
            </div>
        </div>
        @endif

    <script>
        
        function getCurrentLocation() {
            if (navigator.geolocation) {
                // Show loading
                $('#DialogLoading').modal('show');
                
                navigator.geolocation.getCurrentPosition(function(position) {
                    // Get latitude and longitude
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    
                    // Set the value in the textarea
                    $('#kordinat_toko').val(latitude + ',' + longitude);
                    
                    // Hide loading
                    setTimeout(function() {
                        $('#DialogLoading').modal('hide');
                    }, 500);
                    
                    // Show success notification
                    // notification('notification-success', 3000);
                }, function(error) {
                    // Hide loading
                    $('#DialogLoading').modal('hide');
                    
                    // Handle errors
                    var errorMessage = '';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = "Izin akses lokasi ditolak. Harap aktifkan izin lokasi di browser Anda.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = "Informasi lokasi tidak tersedia.";
                            break;
                        case error.TIMEOUT:
                            errorMessage = "Waktu permintaan lokasi habis.";
                            break;
                        default:
                            errorMessage = "Terjadi kesalahan saat mengambil lokasi.";
                            break;
                    }
                    alert(errorMessage);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                });
            } else {
                alert("Geolocation tidak didukung oleh browser ini.");
            }
        }

        $( document ).ready(function() {
            

            getProvinsi()
            
        });

        function getProvinsi() {
            var id_prov = '<?php if($detail_cabang) {echo $detail_cabang->provinsi_id;} else {echo'';} ?>'
            console.log(id_prov)
            $.ajax({
                url: "<?= route('get_provinsi') ?>",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#provinsi_toko').append($('<option>', { 
                        value: '',
                        text : '-- '+"{{__('bahasa.Pilih_Provinsi')}}"+' --'
                    }));
                    $.each(response, function (i, item) {
                        $('#provinsi_toko').append($('<option>', { 
                            value: item.province_id,
                            text : item.province_name 
                        }));
                    });
                    if (id_prov !== '') {
                        $('#provinsi_toko').val(id_prov).change()
                    }
                },
                error: function(error) {
                    console.log("error" + error);
                }
            }); 
        }

        function get_city(id_provinsi) {
            var id_kota = '<?php if($detail_cabang) {echo $detail_cabang->kota_id;} else {echo'';} ?>'

            $('#kota_toko').html('')
            $.ajax({
                url: "<?= route('get_city') ?>",
                method: "POST",
                data : {id_provinsi},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response)
                    $('#kota_toko').append($('<option>', { 
                        value: '',
                        text : '-- '+"{{__('bahasa.Pilih_Kota')}}"+' --'
                    }));
                    
                    $.each(response, function (i, item) {
                        $('#kota_toko').append($('<option>', { 
                            value: item.city_id,
                            text : item.city_name 
                        }));
                        
                        if ((i+1) === response.length && id_kota !== '') {
                            $('#kota_toko').val(id_kota).change()
                        }
                    });
                },
                error: function(error) {
                    console.log("error" + error);
                }
            }); 
        }

        function get_district(id_kota) {
            var id_kecamatan = '<?php if($detail_cabang) {echo $detail_cabang->kecamatan_id;} else {echo'';} ?>'

            $('#kecamatan_toko').html('')
            $.ajax({
                url: "<?= route('get_district') ?>",
                method: "POST",
                data : {id_kota},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response)
                    $('#kecamatan_toko').append($('<option>', { 
                        value: '',
                        text : '-- '+"{{__('bahasa.Pilih_Kecamatan')}}"+' --'
                    }));
                    $.each(response, function (i, item) {
                        $('#kecamatan_toko').append($('<option>', { 
                            value: item.subdistrict_id,
                            text : item.subdistrict_name 
                        }));

                        if ((i+1) === response.length && id_kecamatan !== '') {
                            $('#kecamatan_toko').val(id_kecamatan).change()
                        }
                    });
                },
                error: function(error) {
                    console.log("error" + error);
                }
            }); 
        }
    </script>
    
    <!-- * App Capsule -->

@endsection
