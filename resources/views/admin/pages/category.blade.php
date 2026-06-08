<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@extends('layouts.admin')

@section('title','category')

@section('content')
    <div class="atas d-flex justify-content-between align-items-center mb-4">

        <div class="d-flex gap-2">
            <input 
                type="text"
                class="form-control cat-header"
                placeholder="Search..."
            >
            <button class="btn btn-outline-secondary">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>

        <button class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            New Category
        </button>

    </div>
    <div class="tabel table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th width="80">No</th>
                    <th>Name</th>
                    <th width="120" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <td>1</td>
                <td>IKB</td>
                <td class="text-center">
                    <a href="#" class="btn btn-sm">
                        <i class="bi bi-pencil text-warning"></i>
                    </a>
                    <a href="#" class="btn btn-sm">
                        <i class="bi bi-trash text-danger"></i>
                    </a>
                </td>
            </tbody>
        </table>
    </div>
@endsection