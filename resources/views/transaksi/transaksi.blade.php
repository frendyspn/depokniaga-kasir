@extends('layout.template')

@section('title', $title)

@section('content')
    <!-- App Capsule -->
    
    <div id="appCapsule" class="full-height">

        <div class="section mt-2">
            <div class="section-heading">
                <a href="{{route('transaksi_lihat_semua')}}" class="link">{{__('bahasa.lihat_semua_order')}}</a>
            </div>
            <div class="transactions">
                
            @if(count($list_transaksi) > 0)
                @foreach($list_transaksi as $row)
                <!-- item -->
                <!-- {{json_encode($row)}} -->
                <a href="{{url('transaksi/detail/'.$row->source.'/'.$row->jenis_layanan.'/'.base64_encode($row->id))}}" class="item @if($row->status == 'CANCEL') border border-danger @elseif($row->status == 'PENDING') border border-info @endif">
                    <div class="detail">
                        <div>
                            <strong>
                                {{$row->nama_pemesan}}
                                @if($row->status == 'CANCEL')
                                <span class="badge badge-danger">{{__('bahasa.batal')}}</span>
                                @elseif($row->status == 'PENDING')
                                <span class="badge badge-info">*</span>
                                @endif
                            </strong>
                            <p>{{date('d M Y H:i:s', strtotime($row->tanggal_order))}}</p>
                            <p>{{$row->alamat_antar}}</p>
                        </div>
                    </div>
                    <div class="right">
                        <div class="price">{{__('bahasa.kurs')}}{{number_format($row->total_belanja)}}</div>
                    </div>
                </a>
                <!-- * item -->
                @endforeach
            @else
                <a href="#" class="item">
                    <div class="detail">
                        <div>
                            <strong class="text-danger">Belum Ada Transaksi</strong>
                        </div>
                    </div>
                </a>
            @endif
            </div>
        </div>

    </div>

    
    <!-- * App Capsule -->

@endsection
