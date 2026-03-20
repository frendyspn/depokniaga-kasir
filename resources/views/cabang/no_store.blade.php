@extends('layout.template')

@section('title', 'Buat Toko')

@section('content')
    <!-- App Capsule -->
    <div id="appCapsule" class="full-height">
        <div class="section mt-2" style="display: flex; align-items: center; justify-content: center; min-height: 70vh;">
            <div class="text-center px-3">
                <div class="mb-4">
                    <ion-icon name="storefront-outline" style="font-size: 80px; color: #6c757d;"></ion-icon>
                </div>
                <h2 class="mb-3">Anda Belum Punya Toko</h2>
                <p class="text-muted mb-4">
                    Untuk mulai berjualan, Anda perlu membuat toko terlebih dahulu. 
                    Apakah Anda ingin membuat toko sekarang?
                </p>
                
                <div class="mt-4">
                    <a href="{{route('store_register')}}" class="btn btn-primary btn-lg btn-block mb-2">
                        <ion-icon name="checkmark-circle-outline" class="mr-1"></ion-icon>
                        Ya, Buat Toko Sekarang
                    </a>
                    <a href="{{url('/logout')}}" class="btn btn-secondary btn-lg btn-block">
                        <ion-icon name="close-circle-outline" class="mr-1"></ion-icon>
                        Nanti Saja
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->
@endsection
