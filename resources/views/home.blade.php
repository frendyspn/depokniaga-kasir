@extends('layout.template')

@section('title', $title)

@section('content')
    <!-- App Capsule -->
    <div id="appCapsule" class="full-height">

        <div class="section">

            <div class="listed-detail mt-3">
                <div class="icon-wrapper">
                    <div class="iconbox">
                        <ion-icon name="arrow-down-outline"></ion-icon>
                    </div>
                </div>
                <h3 class="text-center mt-2">{{$toko->nama_reseller}}</h3>
            </div>


        </div>

    </div>

    
    <!-- * App Capsule -->

@endsection
