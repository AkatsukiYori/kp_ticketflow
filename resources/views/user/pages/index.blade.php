<link rel="stylesheet" href="{{ asset('css/user/user.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@extends('layouts.user')

@section('title', 'Buat Tiket')

@section('content')
    <div class="mainIndex">
        <section class="main-kiri">
            <div>
                <h2>
                    Helpdesk Internal
                </h2>
                    <div class="subjudul">
                    <p>
                        Digunakan oleh seluruh tim internal untuk memastikan
                        layanan IT berjalan cepat, transparan dan terdokumentasi.
                    </p>
                    <p>
                        Setiap tiket tercatat, diprioritaskan dan ditangani oleh
                        tim IT terkait
                    </p>
                    </div>
                <div class="button">
                    <a href="{{ route('ticket.index') }}" class="btn btn-new">
                        Buat Tiket Baru
                    </a>
                    <a href="{{ route('cek_status.index') }}" class="btn btn-cek">
                        Cek Status Tiket
                    </a>
                </div>
            </div>
        </section>
        <section class="main-kanan">
            <img src="{{ asset('assets/mainImage.webp') }}"
            alt = ""
            width = "100%"
            height = "100%"
            >
        </section>
    </div>
@endsection