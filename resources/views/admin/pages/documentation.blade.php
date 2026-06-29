@extends('layouts.admin')

@section('title','documentation')

@section('content')
    <section>
        <section class="top-content d-flex justify-content-between">
            <section>
                <input type="text" placeholder="Cari..." id="search" name="search" class="input-search" style="text-indent: 10px">
                <button
                    type="button"
                    id="refresh"
                    class="rounded btn-refresh"
                    data-toggle="tooltip"
                    data-placement="bottom"
                    title="Muat Ulang"
                ><i class="fa-solid fa-arrows-rotate"></i></button>
            </section>
            <button
                type="button"
                name="add"
                id="add"
                class="btn-add border-0"
                data-toggle="tooltip"
                data-placement="bottom"
                title="Tambah Dokumentasi"
            ><i class="fa-solid fa-plus"></i> Dokumentasi Baru</button>
        </section>
        <section class="content-body">
            <table id="datatable" class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </section>
    </section>

    {{-- START: Modal Add Or Update --}}
    <div class="modal fade" id="modalDocumentation" tabindex="-1" aria-labelledby="modalDocumentationLabel" aria-hidden="true">
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
                <form id="formDocumentation" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="modal-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="form-group d-flex flex-column gap-1">
                                <label for="">Kategori <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-control" aria-placeholder="Pilih Kategori">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group d-flex flex-column gap-1">
                                <label for="">Judul <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Masukkan judul">
                            </div>
                            <div class="form-group d-flex flex-column gap-1">
                                <label for="">Deskripsi (Optional)</label>
                                <textarea name="description" id="description" class="form-control" cols="30" rows="5" placeholder="Masukkan deskripsi"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn-cancel border-0"
                                data-bs-dismiss="modal"
                                data-toggle="tooltip"
                                data-placement="bottom"
                                title="Batal"
                            >Batal</button>
                            <button
                                type="submit"
                                class="btn-save border-0"
                                data-toggle="tooltip"
                                data-placement="bottom"
                                title="Simpan"
                            >Simpan</button>
                        </div>
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
                        <p>Dokumentasi akan dihapus secara permanen dan tidak dapat dipulihkan.</p>
                    </center>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn-cancel border-0"
                        data-bs-dismiss="modal"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Batal"
                    >Batal</button>
                    <button
                        type="button"
                        class="btn-delete-modal border-0"
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

        const modalDocumentation = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalDocumentation')
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
            ajax: {
                url: "{{ route('admin.pages.documentation.datatable') }}",
                data: function(d) {
                    d.title = $('#search').val();
                }
            },
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false },
                { data: "title", name: "title", searchable: true },
                { data: "deskripsi", name: "deskripsi", searchable: true },
                { data: "actions", name: "actions", orderable: false, searchable: false }
            ],
            columnDefs: [
                { orderable: false, targets: "_all" },
                { width: "5%", className: "dt-center", targets: 0 },
                { width: "20%", targets: 1 },
                { width: "30%", targets: 2 },
                { className: "dt-left", targets: [1, 2] },
                { width: "20%", className: "dt-right", targets: 3 }
            ],
            dom: "t<'row mt-3'<'col-md-4'i><'col-md-4 text-center'p><'col-md-4 text-end'l>>",
        });
        // END: DataTable

        // START: Event Button Add
        $(document).on('click', '#add', function() {
            $('#modalTitle').text('Tambah Dokumentasi');
            $('#category').val(null);
            $('#title').val(null);
            $('#description').val(null);
            $('#id').val(null);

            modalDocumentation.show();
        });
        // END: Event Button Add

        // START: Submit Handle
        $(document).on('submit', '#formDocumentation', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.pages.documentation.createOrUpdate') }}",
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
                        $('#formDocumentation')[0].reset();

                        modalDocumentation.hide();
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

        // STARTL Event Button Edit
        $(document).on('click', '#btn-edit', function() {
            let url = $(this).data('url');

            $('#id').val(null);
            $('#category').val(null);
            $('#title').val(null);
            $('#description').val(null);
            $('#modalTitle').text('Pembaruan Dokumentasi');

            $.ajax({
                url: url,
                type: "GET",
                success: function(res) {
                    $('#id').val(res.hashed);
                    $('#category').val(res.data.category_id);
                    $('#title').val(res.data.title);
                    $('#description').val(res.data.description);

                    modalDocumentation.show();
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
        })
        // END: Filter & Refresh
    });
</script>
@endsection