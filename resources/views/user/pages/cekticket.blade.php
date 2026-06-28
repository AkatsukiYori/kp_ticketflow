<link rel="stylesheet" href="{{ asset('css/user/user.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@extends('layouts.user')

@section('title', 'Buat Tiket')

@section('content')

    <div class=" row cekticket-header">

    <h2 class="fw-bold mb-1">Cek Status Tiket</h2>
    <p class="text-muted mb-3">
        Cek tiket anda untuk melihat status dan perkembangan penanganan tiket.
    </p>

    <div class="row g-2">

        <div class="col-md-3">
            <input
                type="text"
                class="form-control ticket-input"
                placeholder="Cari nomor tiket..."
            >
        </div>

        <div class="col-md-3">
            <input
                type="text"
                class="form-control ticket-input"
                placeholder="Cari nama pengguna..."
            >
        </div>

        <div class="col-md-3">
            <div class="position-relative">
                <input
                    type="date"
                    class="form-control ticket-input pe-5"
                >
                <i class="bi bi-calendar3 calendar-icon"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="position-relative">
                <input
                    type="date"
                    class="form-control ticket-input pe-5"
                >
                <i class="bi bi-calendar3 calendar-icon"></i>
            </div>
        </div>

    </div>

    <hr class="mt-3">

</div>

@endsection