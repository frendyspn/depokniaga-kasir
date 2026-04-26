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
                            <strong>{{$row->nama_pemesan ?? 'Konsumen Umum'}}</strong>
                            <p class="mb-0">{{date('d M Y H:i:s', strtotime($row->tanggal_order))}}</p>
                            @if($row->alamat_antar)
                            <p class="mb-0 text-muted" style="font-size:0.8em">📍 {{$row->alamat_antar}}</p>
                            @endif

                            {{-- Status transaksi & pengiriman --}}
                            <p class="mb-0 mt-1">
                                @php
                                    $badgeMap = ['Baru'=>'secondary','Diproses'=>'primary','Dikirim'=>'info','Selesai'=>'success','Dikembalikan'=>'warning','Dibatalkan'=>'danger'];
                                    $badgeColor = $badgeMap[$row->status_transaksi] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{$badgeColor}}">{{$row->status_transaksi}}</span>

                                @if($row->status_pengiriman)
                                @php
                                    $shipMap = ['PENDING'=>'info','SEARCH'=>'warning','PICKUP'=>'primary','FINISH'=>'success','CANCEL'=>'danger'];
                                    $shipColor = $shipMap[$row->status_pengiriman] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{$shipColor}}">Kurir: {{$row->status_pengiriman}}</span>
                                @endif
                            </p>

                            {{-- Status pembayaran --}}
                            <p class="mb-0" style="font-size:0.8em">
                                @php
                                    $paymentMap = ['belum_bayar' => 'warning', 'lunas' => 'success'];
                                    $paymentTextMap = ['belum_bayar' => 'Belum Bayar', 'lunas' => 'Lunas'];
                                    $paymentKey = $row->status_pembayaran ?? null;
                                    $paymentColor = $paymentMap[$paymentKey] ?? 'secondary';
                                    $paymentText = $paymentTextMap[$paymentKey] ?? 'Belum Diatur';
                                @endphp
                                <span class="badge badge-{{$paymentColor}}">Pembayaran: {{$paymentText}}</span>
                            </p>

                            {{-- Metode & ongkir --}}
                            <p class="mb-0" style="font-size:0.8em">
                                🚚 {{$row->metode_pengiriman}}
                                @if($row->ongkir > 0)
                                · Rp{{number_format($row->ongkir)}}
                                @endif
                                @if($row->nama_sopir)
                                · <strong>{{$row->nama_sopir}}</strong>
                                @endif
                            </p>
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
