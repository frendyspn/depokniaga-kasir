@extends('layout.template')

@section('title', $title)

@section('content')
    <!-- App Capsule -->
    <div id="appCapsule">

    @if(ENV('APP_BILINGUAL'))
    <div class="section">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{__('bahasa.ganti_bahasa')}}
            </button>
            <div class="dropdown-menu" style="">
                <a class="dropdown-item" href="{{url('change/id')}}">Indonesia</a>
                <a class="dropdown-item" href="{{url('change/en')}}">English</a>
                <a class="dropdown-item" href="{{url('change/kh')}}">កម្ពុជា</a>
            </div>
        </div>
    </div>
    @endif

    <div class="section mt-2 text-center">
            <h1>{{__('bahasa.Masuk')}}</h1>
            <h4>{{__('bahasa.Lengkapi_Form_Dibawah')}}</h4>
        </div>
        <div class="section mb-5 p-2">

        

            <form method="post" action="{{route('proses_login')}}">
                @csrf
                <div class="card">
                    <div class="card-body pb-1">
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <input type="number" class="form-control" id="nomor_hp" name="nomor_hp" placeholder="{{__('bahasa.Nomor_HP')}}">
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <select class="form-control" name="type_login" id="type_login">
                                    <option value="admin">{{__('bahasa.admin')}}</option>
                                    <!-- <option value="owner">{{__('bahasa.owner')}}</option> -->
                                </select>
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="form-links mt-2">
                    <div>
                        <!-- <a href="app-register.html">Register Now</a> -->
                    </div>
                    <!-- <div><a href="app-forgot-password.html" class="text-muted">Forgot Password?</a></div> -->
                </div>

                <div class="form-button-group  transparent">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">{{__('bahasa.Log_in')}}</button>
                </div>

            </form>
        </div>

    </div>

    
    <!-- * App Capsule -->

@endsection
