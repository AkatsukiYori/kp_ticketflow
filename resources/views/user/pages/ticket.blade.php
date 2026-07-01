<link rel="stylesheet" href="{{ asset('css/user/user.css') }}">

@extends('layouts.user')

@section('title', 'Buat Tiket')

@section('content')

<div class="container-fluid px-4">

    <h2 class="fw-bold mb-1">Buat Tiket Baru</h2>

    <p class="text-secondary mb-4">
        Isikan detail permasalahan yang anda alami.
    </p>

    <hr>

    <form id="formTicket" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">

            <div class="col-md-3">
                <label class="form-label">
                    Nama Pengguna <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="pengguna" id="pengguna" aria-placeholder="Pilih Pengguna" required>
                    <option value="">Pilih Pengguna</option>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}">{{ $member->username }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">
                    Nomor WA <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" name="no_wa" id="no_wa" placeholder="Masukkan no wa" autocomplete="off" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">
                    Departemen <span class="text-danger">*</span>
                </label>
                <select class="form-select" name="departemen" id="departemen" aria-placeholder="Pilih Departemen" required>
                    <option value="">Pilih Departemen</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">
                    Lokasi <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="Masukkan lokasi anda" autocomplete="off" required>
            </div>

        </div>

        <div class="row g-3 mt-1" id="section-kategori">

            <div class="col-md-6" id="field-judul">
                <label class="form-label">
                    Judul Tiket <span class="text-danger">*</span>
                </label>

                <input type="text" class="form-control" name="judul_ticket" id="judul_ticket" placeholder="Masukkan judul ticket" autocomplete="off" maxlength="20" required>

                <small class="text-secondary"><span id="current-title-length">0</span> / 20</small>
            </div>

            <div class="col-md-6" id="field-kategori">
                <label class="form-label">
                    Kategori <span class="text-danger">*</span>
                </label>

                <select class="form-select" name="kategori" id="kategori" aria-placeholder="Pilih Kategori" required>
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" data-label="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-none" id="field-modul">
                <label class="form-label">
                    Modul <span class="text-danger">*</span>
                </label>

                <input type="text" class="form-control" name="modul" id="modul" placeholder="Masukkan modul" autocomplete="off" required>
            </div>

            <div class="col-md-3 d-none" id="field-submodul">
                <label class="form-label">
                    Sub Modul <span class="text-danger">*</span>
                </label>

                <input type="text" class="form-control" name="sub_modul" id="sub_modul" placeholder="Masukkan sub modul" autocomplete="off" required>
            </div>

        </div>

        <div class="mt-3">
            <label class="form-label">
                Kendala <span class="text-danger">*</span>
            </label>

            <textarea class="form-control" name="kendala" id="kendala" placeholder="Deskripsikan kendala anda" autocomplete="off" maxlength="1000" required></textarea>

            <small class="text-secondary" id=""><span id="current-desc-length">0</span> / 1000</small>
        </div>

        <div class="mt-4">
            <label class="form-label">
                Catatan Pengguna (Opsional)
            </label>

            <input type="text" class="form-control" name="note" id="note" placeholder="Masukkan catatan">
        </div>

        <div class="mt-4">
            <label class="form-label">
                Lampiran (Opsional)
            </label>

            <input type="file" id="attachment" name="attachment">
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">

            <button type="submit" class="btn btn-primary">
                Buat Tiket
            </button>

            <button type="button" class="btn btn-danger" id="btn-back">
                Kembali
            </a>

        </div>

    </form>

</div>

{{-- START: Toast display --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <span id="toastIcon"></span>
            <strong class="me-auto" id="toastTitle"></strong>

            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastBody">
            
        </div>
    </div>
</div>
{{-- END: Toast display --}}

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        // START: Init bootstrap 5 toast
        const toast = new bootstrap.Toast(
            document.getElementById('liveToast')
        );

        const pond = FilePond.create(document.querySelector('#attachment'), {
            server: {
                process: "{{ route('ticket.uploadTemp') }}",
                revert: "{{ route('ticket.uploadRevert') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }
        });
        // END: Init bootstrap 5 toast

        // START: Set value to null when first render
        $('#kategori').val(null);
        $('#departemen').val(null);
        $('#pengguna').val(null);
        $('#attachment').val(null);
        $('#field-judul').removeClass('col-md-3').addClass('col-md-6');
        $('#field-kategori').removeClass('col-md-3').addClass('col-md-6');
        $('#field-modul').addClass('d-none');
        $('#field-submodul').addClass('d-none');
        // END: Set value to null when first render

        // START: Category change value
        $(document).on('change', '#kategori', function(e) {
            let label = $(this).find(':selected').data('label');

            $('#modul').val(null).prop('required', false);
            $('#sub_modul').val(null).prop('required', false);

            $('#field-judul').removeClass('col-md-3').addClass('col-md-6');
            $('#field-kategori').removeClass('col-md-3').addClass('col-md-6');
            $('#field-modul').addClass('d-none');
            $('#field-submodul').addClass('d-none');

            if(label === "IKB" || label === 'ikb') {
                $('#field-judul').removeClass('col-md-6').addClass('col-md-3');
                $('#field-kategori').removeClass('col-md-6').addClass('col-md-3');
                $('#field-modul').removeClass('d-none');
                $('#field-submodul').removeClass('d-none');

                $('#modul').prop('required', true);
                $('#sub_modul').prop('required', true);
            }
        });
        // END: Category change value

        // START: Form create
        $(document).on('submit', '#formTicket', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('ticket.create') }}",
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(res) {
                    if(res.status == true) {
                        $('#toastTitle').text("Success");
                        $('#toastBody').text('Ticket No : ' + res.ticket_no);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);
                        toast.show();

                        $('#formTicket')[0].reset();
                    } else {
                        $('#toastTitle').text("Error");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                        toast.show();
                    }
                }
            })
        });
        // END: Form create

        // START: Input length counter
        $(document).on('input', '#judul_ticket', function(e) {
            let currentLength = $(this).val().length;
            $('#current-title-length').text(currentLength);
        });

        $(document).on('input', '#kendala', function(e) {
            let currentLength = $(this).val().length;
            $('#current-desc-length').text(currentLength);
        });
        // END: Input length counter

        // START: Event Button Back
        $(document).on('click', '#btn-back', function() {
            window.location.href = "{{ route('home') }}";
        });
        // END: Event Button Back
    });
</script>
@endsection