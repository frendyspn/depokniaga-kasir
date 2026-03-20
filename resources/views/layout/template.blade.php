<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
    <meta name="description" content="Finapp HTML Mobile Template">
    <meta name="keywords" content="bootstrap, wallet, banking, fintech mobile template, cordova, phonegap, mobile, html, responsive" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png')}}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/icon/192x192.png')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="manifest" href="{{ asset('pwa/__manifest.json') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>
<body>
    <!-- loader -->
    <div id="loader">
        <img src="{{asset('assets/img/loading-icon.png')}}" alt="icon" class="loading-icon">
    </div>
    <!-- * loader -->

    <!-- App Header -->
    @if(isset($header))
        @if($header == 'goback')
        <div class="appHeader no-border transparent position-absolute">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">{{$title}}</div>
            <div class="right">
            </div>
        </div>
        @endif
    @endif
    
    <!-- * App Header -->


    @yield('content')


    @if(isset($menu))
        <div class="appBottomMenu no-border">
            <a href="{{route('home')}}" class="item <?php if($title == 'Home') echo 'active' ?>" >
                <div class="col">
                    <ion-icon name="home-outline"></ion-icon>
                    <strong>{{__('bahasa.Beranda')}}</strong>
                </div>
            </a>
            @if(Session::get('level') != 'admin')
            <a href="{{route('cabang')}}" class="item <?php if($title == 'Cabang') echo 'active' ?>">
                <div class="col">
                    <ion-icon name="storefront-outline"></ion-icon>
                    <strong>{{__('bahasa.cabang')}}</strong>
                </div>
            </a>
            <a href="{{route('user')}}" class="item <?php if($title == 'User') echo 'active' ?>">
                <div class="col">
                    <ion-icon name="person-outline"></ion-icon>
                    <strong>{{__('bahasa.user')}}</strong>
                </div>
            </a>
            @endif

            @if(Session::get('level') != 'owner')
            <a href="{{route('pos')}}" class="item <?php if($title == 'Pos') echo 'active' ?>">
                <div class="col">
                    <ion-icon name="bag-handle-outline"></ion-icon>
                    <strong>POS</strong>
                </div>
            </a>
            @endif
            
            <a href="{{route('transaksi')}}" class="item <?php if($title == 'Transaksi') echo 'active' ?>">
                <div class="col">
                    <ion-icon name="bag-check-outline"></ion-icon>
                    <strong>{{__('bahasa.transaksi')}}</strong>
                </div>
            </a>

            <a href="{{route('produk')}}" class="item <?php if($title == 'Produk') echo 'active' ?>">
                <div class="col">
                    <ion-icon name="bag-check-outline"></ion-icon>
                    <strong>Produk</strong>
                </div>
            </a>

            <!-- <a href="#" class="item">
                <div class="col">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <strong>Item 4</strong>
                </div>
            </a> -->
            <a href="{{route('logout')}}" class="item">
                <div class="col">
                    <ion-icon name="log-out-outline"></ion-icon>
                    <strong>{{__('bahasa.Keluar')}}</strong>
                </div>
            </a>
        </div>
    @endif

    <!-- ========= JS Files =========  -->
    <!-- Bootstrap -->
    <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="{{ asset('assets/js/plugins/splide/splide.min.js') }}"></script>
    <!-- Base Js File -->
    <script src="{{ asset('assets/js/base.js') }}"></script>

    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>

    @if ($errors->any())
        <div id="notification-login" class="notification-box">
            <div class="notification-dialog ios-style bg-danger">
                <div class="notification-header">
                    <div class="in">
                        <strong>Perhatian!</strong>
                    </div>
                    <div class="right">
                        <a href="#" class="close-button">
                            <ion-icon name="close-circle"></ion-icon>
                        </a>
                    </div>
                </div>
                <div class="notification-content">
                    <div class="in">
                        @foreach ($errors->all() as $error)
                        <div class="text-bold">{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <script>
            setTimeout(() => {
                notification('notification-login')
            }, 500);
            
        </script>
    @endif

    @if (Session::get('error_msg'))
        <div id="notification-login" class="notification-box">
            <div class="notification-dialog ios-style bg-danger">
                <div class="notification-header">
                    <div class="in">
                        <strong>Perhatian!</strong>
                    </div>
                    <div class="right">
                        <a href="#" class="close-button">
                            <ion-icon name="close-circle"></ion-icon>
                        </a>
                    </div>
                </div>
                <div class="notification-content">
                    <div class="in">
                        <div class="text-bold">{{ Session::get('error_msg') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            setTimeout(() => {
                notification('notification-login')
            }, 500);
            
        </script>
    @endif


    

    <div class="modal fade dialogbox" id="DialogLoading" data-bs-backdrop="static" tabindex="-10" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-icon">
                    <div class="spinner-grow text-primary" role="status"></div>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">Mohon Tunggu</h5>
                </div>
                
            </div>
        </div>
    </div>

    <div id="notifError" class="notification-box">
        <div class="notification-dialog ios-style">
            <div class="notification-header">
                <div class="in">
                    <strong>Perhatian!</strong>
                </div>
                <div class="right">
                    <a href="#" class="close-button">
                        <ion-icon name="close-circle"></ion-icon>
                    </a>
                </div>
            </div>
            <div class="notification-content">
                <div class="in">
                    <div class="text-bold" id="pesan-notif"></div>
                </div>
            </div>
        </div>
    </div>

</body>

<script>

    $( document ).ready(function() {
        $(".notification-box").hasClass("show") ? console.log('yes') : null;
    });

    function notif(type, msg) {
        notification('notifError')
        $('#notifError .notification-dialog').addClass(type)
        $('#notifError #pesan-notif').html(msg)
        console.log(msg)

        setTimeout(() => {
            $('#notifError').removeClass('show');
        }, 5000);
    }

    function loaderShow() {
        $('#DialogLoading').modal('show')
        console.log('show')
    }

    function loaderHide() {
        $('#DialogLoading').modal('hide')
        console.log('hide')
    }

    function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
        try {
            decimalCount = Math.abs(decimalCount);
            decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

            const negativeSign = amount < 0 ? "-" : "";

            let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
            let j = (i.length > 3) ? i.length % 3 : 0;

            return negativeSign +
            (j ? i.substr(0, j) + thousands : '') +
            i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) +
            (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
        } catch (e) {
            console.log(e)
        }
    };
</script>

    @if (Session::get('sukses'))
        <script>
            notif('bg-primary', "{{ Session::get('sukses') }}")
        </script>
    @endif

    @if (Session::get('error_msg'))
        <script>
            notif('bg-danger', "{{ Session::get('error_msg') }}")
        </script>
    @endif

    <script src="{{ asset('assets/js/notif.js') }}"></script>

</html>