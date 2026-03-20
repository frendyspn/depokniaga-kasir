@extends('layout.template')

@section('title', $title)

@section('content')
    <!-- App Capsule -->
    <!-- {{json_encode($list_user)}} -->
    <div id="appCapsule" class="full-height">

        <div class="section mt-2">
            <div class="section-heading">
                <h4>List User</h4>
                <a href="{{route('user_tambah')}}" class="link">{{__('bahasa.tambah')}}</a>
            </div>
            <div class="transactions">
                @if($list_user_code != '200')
                <h4 class="text-center text-danger">{{$list_user->Message}}</h4>
                @else
                @foreach($list_user as $row)
                    <a href="{{url('user_edit/'.$row->id_konsumen)}}" class="item">
                        <div class="detail">
                            <div>
                                <strong>{{$row->nama_lengkap}}</strong>
                                <p>{{$row->nama_reseller}}</p>
                            </div>
                        </div>
                        <div class="right">
                            <!-- <div class="price text-danger"> - $ 150</div> -->
                        </div>
                    </a>
                @endforeach
                @endif
                <!-- item -->
                
                <!-- * item -->
            </div>
        </div>

    </div>

    
    <!-- * App Capsule -->

@endsection
