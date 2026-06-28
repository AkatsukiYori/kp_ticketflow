<link rel="stylesheet" href="{{ asset('css/user/user.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@extends('layouts.user')

@section('title', 'Buat Tiket')

@section('content')

<div class="container-fluid px-4">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Cek Status Ticket</h2>
        <p class="text-muted mb-0">
            Cek ticket anda untuk melihat status dan perkembangan penanganan ticket.
        </p>
    </div>
        <div class="row g-3">

            <div class="col-lg-3">
                <input type="text"
                    class="form-control"
                    name="ticket_number"
                    placeholder="Cari nomor ticket...">
            </div>

            <div class="col-lg-3">
                <input type="text"
                    class="form-control"
                    name="user_name"
                    placeholder="Cari nama pengguna...">
            </div>

            <div class="col-lg-3">
                <input type="date"
                    class="form-control"
                    name="start_date">
            </div>

            <div class="col-lg-3">
                <input type="date"
                    class="form-control"
                    name="end_date">
            </div>

        </div>

    <div class="d-flex flex-column gap-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="flex-grow-1">

                        <small class="text-muted">
                            #TKT-001 &nbsp; • &nbsp;
                            28 Juni 2026, 18:33 &nbsp; • &nbsp;
                            Glarista
                        </small>

                        <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">

                            <h5 class="fw-bold mb-0">
                                Kangen Alex
                            </h5>

                            <span class="badge rounded-pill text-bg-warning">
                                On Progress
                            </span>

                            <span class="badge rounded-pill text-bg-light border text-dark">
                                High
                            </span>

                        </div>

                        <p class="text-muted mt-2 mb-3">
                            Habis ngedate belum cipika cipiki jadi kangen
                        </p>

                        <button class="btn btn-light btn-sm border">
                            <i class="bi bi-list"></i> Logs
                        </button>

                    </div>

                    <a href="#" class="btn btn-outline-primary rounded-circle ms-3">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column gap-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="flex-grow-1">

                        <small class="text-muted">
                            #TKT-001 &nbsp; • &nbsp;
                            28 Juni 2026, 18:33 &nbsp; • &nbsp;
                            Glarista
                        </small>

                        <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">

                            <h5 class="fw-bold mb-0">
                                Kangen Alex
                            </h5>

                            <span class="badge rounded-pill text-bg-warning">
                                On Progress
                            </span>

                            <span class="badge rounded-pill text-bg-light border text-dark">
                                High
                            </span>

                        </div>

                        <p class="text-muted mt-2 mb-3">
                            Habis ngedate belum cipika cipiki jadi kangen
                        </p>

                        <button class="btn btn-light btn-sm border">
                            <i class="bi bi-list"></i> Logs
                        </button>

                    </div>

                    <a href="#" class="btn btn-outline-primary rounded-circle ms-3">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column gap-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div class="flex-grow-1">

                        <small class="text-muted">
                            #TKT-001 &nbsp; • &nbsp;
                            28 Juni 2026, 18:33 &nbsp; • &nbsp;
                            Glarista
                        </small>

                        <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">

                            <h5 class="fw-bold mb-0">
                                Kangen Alex
                            </h5>

                            <span class="badge rounded-pill text-bg-warning">
                                On Progress
                            </span>

                            <span class="badge rounded-pill text-bg-light border text-dark">
                                High
                            </span>

                        </div>

                        <p class="text-muted mt-2 mb-3">
                            Habis ngedate belum cipika cipiki jadi kangen
                        </p>

                        <button class="btn btn-light btn-sm border">
                            <i class="bi bi-list"></i> Logs
                        </button>

                    </div>

                    <a href="#" class="btn btn-outline-primary rounded-circle ms-3">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection