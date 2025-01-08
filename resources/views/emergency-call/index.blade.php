@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="row mx-1 my-1">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title w-100">
                    <h4 class="card-title d-inline-block mb-0">Panggilan Darurat</h4>
                </div>
            </div>
            <div class="card-body text-left m-0">
                <div class="container ml-3 mr-4">
                    <div class="row">
                        <div class="col-lg-5 mx-0 text-center">
                            <img src="{{ asset('assets/images/pages/emergency-call.png') }}" class="img-fluid rounded mb-4" alt="Responsive image" style="width: 400px; height: auto;">
                        </div>
                        <div class="col-lg-6 ml-5 mt-3">
                            <h2 class="mt-5 mb-4 mr-4">SEGERA LAKUKAN
                                <br>
                                PANGGILAN DARURAT
                            </h2>
                            <p class="mr-4 mb-4">Lakukan panggilan darurat ketika anda membutuhkan pelayanan sesegera mungkin. Kami akan melakukan yang terbaik untuk anda, kepuasan anda adalah prioritas bagi kami.</p>
                            <a href="https://wa.me/6285732868153" target="_blank">
                                <button type="button" class="mr-4 btn btn-primary">
                                    <i class="ri-whatsapp-line"></i>Hubungi Sekarang
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection