@extends('layout.template')

@section('title', $title)

@section('content')
    <!-- App Capsule -->
    
    
    <div id="appCapsule" class="full-height">
        
        <form method="post" action="{{route('user_simpan')}}">
            @csrf
            <div class="section mt-2">
                <div class="section-title">{{__('bahasa.user')}}</div>
                <div class="card">
                    <div class="card-body">
                    <input type="hidden" class="form-control" id="id_user" name="id_user"  value="<?php if($detail_user) {echo $detail_user->id_konsumen;} else {echo'-';} ?>">
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="nama_user">{{__('bahasa.nama_pengguna')}}</label>
                                <input type="text" class="form-control" id="nama_user" name="nama_user" placeholder="{{__('bahasa.nama_pengguna')}}" value="<?php if($detail_user) {echo $detail_user->nama_lengkap;} else {echo'';} ?>" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="hp_user">{{__('bahasa.Nomor_HP')}}</label>
                                <input type="number" class="form-control" id="hp_user" name="hp_user" placeholder="{{__('bahasa.Nomor_HP')}}" value="<?php if($detail_user) {echo $detail_user->no_hp;} else {echo'';} ?>" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="email_user">{{__('bahasa.Email')}}</label>
                                <input type="email" class="form-control" id="email_user" name="email_user" placeholder="{{__('bahasa.Email')}}" value="<?php if($detail_user) {echo $detail_user->email;} else {echo'';} ?>" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="tanggal_lahir">{{__('bahasa.Tanggal_Lahir')}}</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" placeholder="{{__('bahasa.Tanggal_Lahir')}}" value="<?php if($detail_user) {echo $detail_user->tanggal_lahir;} else {echo'';} ?>" required>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="cabang_user" >{{__('bahasa.cabang')}}</label>
                                <select name="cabang_user" id="cabang_user" class="form-control" required>
                                    <option value="">--{{__('bahasa.pilih_cabang')}}--</option>
                                    @foreach($list_cabang as $row)
                                    <?php 
                                        $selected = '';
                                        if($detail_user) {
                                        if($detail_user->id_reseller == $row->id_reseller){
                                            $selected = 'selected';
                                        } } ?>
                                    <option {{$selected}} value="{{$row->id_reseller}}">{{strtoupper($row->nama_reseller)}}</option>
                                    @endforeach
                                </select>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            

            <div class="section mb-7 p-2">
                <div class="form-button transparent">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">{{__('bahasa.simpan')}}</button>
                    @if($detail_user)
                    <button type="button" class="btn btn-secondary btn-block btn-lg mt-5" onclick="notification('verif-delete')">{{__('bahasa.btn_title_hapus')}}</button>
                    @endif
                </div>
            </div>
            

        </form>
    </div>


        @if($detail_user)
        <div id="verif-delete" class="notification-box" tabindex="-1">
            <div class="notification-dialog ios-style bg-danger">
                <div class="notification-header">
                    <div class="in">
                        <strong>{{__('bahasa.notif_user_akan_dihapus')}}</strong>
                    </div>
                    <div class="right">
                        <a href="#" class="close-button">
                            <ion-icon name="close-circle"></ion-icon>
                        </a>
                    </div>
                </div>
                <div class="notification-content">
                    <div class="in">
                        <h3 class="subtitle">{{__('bahasa.notif_hapus_user')}}?</h3>
                        <div class="text">
                            {{__('bahasa.notif_ya_konfirmasi')}}
                        </div>
                    </div>
                </div>
                <div class="notification-footer">
                    <a href="{{url('user_hapus/'.$detail_user->id_konsumen)}}"  class="notification-button" onclick="$('#DialogLoading').modal('show')">
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
        
        

        $( document ).ready(function() {
            

            
            
        });

        
    </script>
    
    <!-- * App Capsule -->

@endsection
