@extends('layout.template')

@section('title', $title)

@section('content')
    <!-- App Capsule -->
    <div id="appCapsule">

    <div class="section mt-2 text-center">
            <h1>OTP</h1>
            <h4>{{__('bahasa.masukan_otp')}}</h4>
            <small class="text-danger font-weight-bold">{{__('bahasa.jangan_refresh_halaman_otp')}}</small>
        </div>
        <div class="section mb-5 p-2">

        

            <form method="post" action="{{route('proses_otp')}}">
                @csrf
                <div class="form-group basic">
                    <input type="hidden" id="nomor_hp" name="nomor_hp" value="{{Session::get('nomor')}}">
                    <input type="hidden" id="type_login" name="type_login" value="{{Session::get('level')}}">
                    <input type="number" class="form-control verification-input" id="otp" name="otp" placeholder="••••••"
                        maxlength="7" required>
                </div>

                <div class="form-button-group transparent">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">{{__('bahasa.verifikasi')}}</button>
                </div>

            </form>
        </div>

    </div>

    @if (Session::get('otp'))
        <div id="notification-otp" class="notification-box show">
            <div class="notification-dialog ios-style bg-warning">
                <div class="notification-content">
                    <div class="in">
                        <div class="font-weight-bold">{{ Session::get('otp') }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- * App Capsule -->

@endsection
