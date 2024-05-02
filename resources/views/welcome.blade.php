@extends('layout.template')

@section('title', $title)

@section('content')
    <!-- App Capsule -->
    <div id="appCapsule">

        <div class="section mt-2 text-center">
            <h4>Selamat Datang Di</h4>
            <h1>Satu Kasir</h1>
        </div>
        <div class="section mb-5 p-2 row">
            <a href="{{route('login')}}" class="btn btn-primary col btn-lg mx-1">Masuk</a>
            <button class="btn btn-secondary col btn-lg mx-1">Daftar</button>
        </div>

    </div>
    <!-- * App Capsule -->

@endsection
