<link rel="stylesheet" href="{{ asset('css/user/user.css') }}">

@extends('layouts.user')

@section('title', 'Buat Tiket')

@section('content')

<div class="container-fluid py-4">

    <h2 class="fw-bold mb-1">Buat Tiket Baru</h2>

    <p class="text-secondary mb-4">
        Isikan detail permasalahan yang anda alami.
    </p>

    <hr>

    <form>

        <div class="row g-3">

            <div class="col-md-3">
                <label class="form-label">
                    Nama Pengguna <span class="text-danger">*</span>
                </label>
                <select class="form-select">
                    <option>Pilih Pengguna</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">
                    Nomor WA <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control">
            </div>

            <div class="col-md-3">
                <label class="form-label">
                    Departemen <span class="text-danger">*</span>
                </label>
                <select class="form-select">
                    <option>Pilih Departemen</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">
                    Lokasi <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control">
            </div>

        </div>

        <div class="row g-3 mt-1">

            <div class="col-md-6">
                <label class="form-label">
                    Judul Tiket <span class="text-danger">*</span>
                </label>

                <input type="text" class="form-control">

                <small class="text-secondary">0/20</small>
            </div>

            <div class="col-md-6">
                <label class="form-label">
                    Kategori <span class="text-danger">*</span>
                </label>

                <select class="form-select">
                    <option>Pilih Kategori</option>
                </select>
            </div>

        </div>

        <div class="mt-3">
            <label class="form-label">
                Kendala <span class="text-danger">*</span>
            </label>

            <textarea class="form-control"></textarea>

            <small class="text-secondary">0/1000</small>
        </div>

        <div class="mt-4">
            <label class="form-label">
                Catatan Pengguna (Opsional)
            </label>

            <input type="text" class="form-control">
        </div>

        <div class="mt-4">
            <label class="form-label">
                Lampiran (Opsional)
            </label>

            <div class="border rounded bg-light py-5 text-center">
                Drag & Drop file atau
                <a href="#">Browse</a>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">

            <button class="btn btn-primary">
                Buat Tiket
            </button>

            <a href="{{ route('home') }}" class="btn btn-danger">
                Kembali
            </a>

        </div>

    </form>

</div>

@endsection