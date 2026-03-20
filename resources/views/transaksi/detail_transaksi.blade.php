@extends('layout.template')

@section('title', $title)

@section('content')
    <!-- App Capsule -->
    
    <div id="appCapsule" class="full-height">

        <div class="section mt-2">
            <div id="content-order">

                <div class="card mb-1">
                    <ul class="listview flush transparent no-line image-listview detailed-list mt-1 mb-1">
                        @php $total = 0; @endphp
                        @foreach($dt_detail as $dtl)
                        <!-- item -->
                        <li>
                            <a href="#" class="item">
                                <div class="in">
                                    <div>
                                        <strong>{{$dtl->nama_produk}}</strong>
                                        <div class="text-small text-secondary">{{$dtl->jumlah}} x {{__('bahasa.kurs')}}{{number_format($dtl->harga_jual-$dtl->diskon)}}</div>
                                    </div>
                                    <div class="text-end">
                                        <strong>{{__('bahasa.kurs')}}{{number_format($dtl->jumlah*($dtl->harga_jual-$dtl->diskon))}}</strong>
                                        <!-- <button class="btn btn-primary btn-sm col-12" onclick="editPesanan(255,'DELL XPS 15 9575 TOUCHSCREEN I7 8706G LAPTOP 2 IN 1 RENDERING GAMING','',1,2600,124)">Edit</button> -->
                                    </div>
                                </div>
                            </a>
                        </li>
                        <!-- * item -->
                        @php $total = $total + ($dtl->jumlah*($dtl->harga_jual-$dtl->diskon)); @endphp
                        @endforeach
                    </ul>
                </div>

                
            <ul class="listview image-listview mb-2">
                <li>
                    <div class="item">
                        <div class="in">
                            <div>
                                <header class="btn btn-sm btn-primary">{{$dt_header->jenis_layanan}}</header>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item">
                        <div class="icon-box bg-primary">
                            <a class="text-white" href="#"><ion-icon name="location-outline" role="img" class="md hydrated" aria-label="location outline"></ion-icon></a>
                        </div>
                        <div class="in">
                            <div>
                                <header>{{__('bahasa.tujuan')}}</header>
                                {{$dt_header->nama_lengkap}}
                                <footer>{{$dt_header->alamat_antar}}</footer>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item">
                        <div class="icon-box bg-primary">
                            <ion-icon name="cash-outline" role="img" class="md hydrated" aria-label="cash outline"></ion-icon>
                        </div>
                        <div class="in">
                            <h3>
                            {{__('bahasa.kurs')}}{{number_format($total)}}
                            </h3>
                        </div>
                    </div>
                </li>

                @if($dt_header->source == 'APPS' && $dt_header->status == 'PENDING')
                <li>
                    <div class="item row">
                        <a class="btn btn-primary col-6" href="{{url('transaksi/confirm/'.$dt_header->id.'/NEW')}}" >{{__('bahasa.ambil_order')}}</a>
                        <button class="btn btn-danger col-6" data-bs-toggle="collapse" data-bs-target="#accordion003" aria-expanded="true">{{__('bahasa.batal')}}</button>
                        <!-- <a class="btn btn-danger col-6" href="{{url('transaksi/confirm/'.$dt_header->id.'/CANCEL')}}" >{{__('bahasa.batal')}}</a> -->
                    </div>
                </li>
                <li>
                    <div id="accordion003" class="accordion-collapse collapse col-12" data-bs-parent="#accordionExample2" style="">
                        <div class="accordion-body">
                            <form action="{{route('transaksi/cancel_transaksi')}}" method="post">
                                @csrf
                                <input type="hidden" id="batal_id" name="batal_id" value="{{$dt_header->id}}">
                                <textarea class="form-control display-block" name="batal_alasan" id="batal_alasan" placeholder="Alasan Pembatalan" required></textarea>
                                <input type="submit" value="Batalkan" class="btn btn-secondary btn-block mt-1">
                            </form>
                        </div>
                    </div>
                </li>
                @endif


                @if($dt_header->source == 'APPS' && $dt_header->status != 'PENDING')
                <li>
                <div class="section mt-1 mb-2">
                    <div class="section-title">{{__('bahasa.status_order')}} <span style="color:blue" onclick="location.reload()">{{__('bahasa.refresh')}}</span></div>
                    <div class="card">
                        <!-- timeline -->
                        <div class="timeline ms-3">
                            
                            @foreach($timeline as $row)
                            @php
                            if($row->log_status == 'NEW'){
                                $status = __('bahasa.mencari_driver');
                            } else if($row->log_status == 'PROCESS'){
                                $status = __('bahasa.mencari_driver');
                            } else if($row->log_status == 'ONTHEWAY'){
                                $status = __('bahasa.pesanan_sedang_diantar');
                            } else if($row->log_status == 'FINISH'){
                                $status = __('bahasa.pesanan_sampai');
                            } else if($row->log_status == 'PENDING'){
                                $status = __('bahasa.pesanan_konfirm_toko');
                            } else {
                                $status = $row->log_status;
                            }

                            @endphp
                            <div class="item">
                                <div class="dot {{$warna_timeline[rand(0,count($warna_timeline)-1)]}}"></div>
                                <div class="content">
                                    <h4 class="title">{{$status}}</h4>
                                    <div class="text">{{date('d-M-Y H:i:s', strtotime($row->log_time))}}</div>
                                    @if($row->log_status == 'PROCESS')
                                    <h4 class="title mt-2">{{$kurir->nama_lengkap}}</h4>
                                    <div class="text">{{$kurir->merek}} - {{$kurir->plat_nomor}}</div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <!-- * timeline -->
                    </div>
                </div>
                </li>

                @endif
            </ul>
            
                    
            </div>
        </div>

        
    </div>

    
    <!-- * App Capsule -->

@endsection
