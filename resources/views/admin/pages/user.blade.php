@extends('layouts.admin')

@section('title','user')

@section('content')
    <section>
        <section class="top-content d-flex justify-content-between">
            <section>
                <input type="text" placeholder="Cari..." id="search" name="search" class="input-search" style="text-indent: 10px">
                <button
                    type="button"
                    id="refresh"
                    name="refresh"
                    class="btn-refresh"
                    data-toggle="tooltip"
                    data-placement="bottom"
                    title="Muat Ulang"
                ><i class="fa-solid fa-arrows-rotate"></i></button>
            </section>
            <button
                type="button"
                name="add"
                id="add"
                class="btn-add"
                data-toggle="tooltip"
                data-placement="bottom"
                title="Tambah Pengguna"
            ><i class="fa-solid fa-plus"></i> Tambah Pengguna</button>
        </section>
        <section class="content-body">
            <div class="table-responsive">
                <table id="datatable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </section>
    </section>

    {{-- START: Modal Add Or Update --}}
    <div class="modal fade" id="modalUser" tabindex="-1" aria-labelledby="modalUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5 fw-bold" id="modalTitle"></h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Tutup"
                    ></button>
                </div>
                <form id="formUsers" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="modal-body">
                        <div class="form-group d-flex flex-column gap-1">
                            <label for="">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama pengguna" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn-cancel"
                            data-bs-dismiss="modal"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="Batal"
                        >Batal</button>
                        <button
                            type="submit"
                            class="btn-save"
                            data-toggle="tooltip"
                            data-placement="bottom"
                            title="Simpan"
                        >Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- END: Modal Add Or Update --}}

    {{-- START: Modal Delete --}}
    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 fw-bold" id="modalDeleteLabel">Konfirmasi Hapus</h1>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Tutup"
                    ></button>
                </div>
                <div class="modal-body">
                    <center>
                        <p>Pengguna akan dihapus secara permanen dan tidak dapat dipulihkan.</p>
                    </center>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn-cancel"
                        data-bs-dismiss="modal"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Batal"
                    >Batal</button>
                    <button
                        type="button"
                        class="btn-delete-modal"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Hapus"
                    >Hapus</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Modal Delete --}}

    {{-- START: Toast --}}
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
            <div class="toast-header">
                <span id="toastIcon"></span>
                <strong class="me-auto" id="toastTitle"></strong>
                {{-- <small>11 mins ago</small> --}}
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastBody">
                
            </div>
        </div>
    </div>
    {{-- END: Toast --}}
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // START: Init
            $('#search').val(null);

            const toastEl = document.getElementById('liveToast');
            const toast = new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 3000
            });

            const modalUser = bootstrap.Modal.getOrCreateInstance(
                document.getElementById('modalUser')
            );

            const modalDelete = bootstrap.Modal.getOrCreateInstance(
                document.getElementById('modalDelete')
            );
            // END: Init

            // START: DataTable
            let table = new DataTable('#datatable', {
                responsive: true,
                serverSide: true,
                ordering: false,
                layout: {
                    topStart: null,
                    topEnd: null,

                    bottomStart: 'info',
                    bottomEnd: 'pageLength'
                },
                ajax: {
                    url: "{{ route('admin.pages.member.datatable') }}",
                    data: function(d) {
                        d.username = $('#search').val();
                    }
                },
                columns: [
                    { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false },
                    { data: "username", name: "username", searchable: true },
                    { data: "actions", name: "actions", orderable: false, searchable: false }
                ],
                columnDefs: [
                    { orderable: false, targets: "_all" },
                    { width: "5%", className: "dt-center", targets: 0 },
                    { className: "dt-left", targets: 1 }, 
                    { width: "20%", className: "dt-right", targets: 2 }
                ]
            });
            // END: DataTable

            // START: Event Button Add
            $(document).on('click', '#add', function() {
                $('#modalTitle').text('Tambah Pengguna');
                $('#name').val(null);
                $('#id').val(null);

                modalUser.show();
            });
            // END: Event Button Add

            // START: Submit Handle
            $(document).on('submit', '#formUsers', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('admin.pages.member.createOrUpdate') }}",
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if(res.status === true) {
                            $('#toastTitle').text("Berhasil");
                            $('#toastBody').text(res.message);
                            $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);

                            toast.show();
                            table.ajax.reload();
                            $('#formUsers')[0].reset();
                            
                            modalUser.hide();
                        } else {
                            $('#toastTitle').text("Gagal");
                            $('#toastBody').text(res.message);
                            $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                            toast.show();
                        }
                    }
                });
            });
            // END: Submit Handle

            // START: Event Button Edit
            $(document).on('click', '#btn-edit', function() {
                let url = $(this).data('url');
                
                $('#id').val(null);
                $('#name').val(null);
                $('#modalTitle').text('Pembaruan Pengguna');

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(res) {
                        $('#id').val(res.hashed);
                        $('#name').val(res.data.username);

                        modalUser.show();
                    }
                });
            });
            // END: Event Button Edit

            // START: Event Button Delete
            $(document).on('click', '#btn-delete', function() {
                let url = $(this).data('url');

                $(document).on('click', ".btn-delete-modal", function() {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        success: function(res) {
                            if(res.status == true) {
                                $('#toastTitle').text("Berhasil");
                                $('#toastBody').text(res.message);
                                $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);

                                toast.show();
                                table.ajax.reload();
                                modalDelete.hide();
                            } else {
                                $('#toastTitle').text("Gagal");
                                $('#toastBody').text(res.message);
                                $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);

                                toast.show();
                            }
                        }
                    });
                });

                modalDelete.show();
            });
            // END: Event Button Delete

            // START: Filter & Refresh
            $(document).on('keyup click', '#search, #refresh', function() {
                table.ajax.reload();
            });
            // END: Filter & Refresh
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
@endsection