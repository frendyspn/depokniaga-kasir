@extends('layout.template')

@section('title', $title)

@section('content')
    <!-- App Capsule -->
    <!-- {{json_encode($list_cabang)}} -->
    <!-- {{$list_cabang_code}} -->
    <div id="appCapsule" class="full-height">

        <div class="section mt-2">
            <div class="section-heading">
                <h4>List Cabang</h4>
                <a href="{{route('cabang_tambah')}}" class="link">{{__('bahasa.tambah')}}</a>
            </div>
            <div class="transactions">
                @if($list_cabang_code != '200')
                <h4 class="text-center text-danger">{{$list_cabang->Message}}</h4>
                @else
                @foreach($list_cabang as $row)
                    <a href="{{url('cabang_edit/'.$row->id_reseller)}}" class="item">
                        <div class="detail">
                            <div>
                                <strong>{{$row->nama_reseller}}</strong>
                                <p>{{$row->kota}}</p>
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
