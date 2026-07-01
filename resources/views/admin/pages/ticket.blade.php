<style>
.dropdown {
    position: relative;
    display: inline-block;
    font-family: sans-serif;
}

/* tombol utama */
.dropdown-btn {
    cursor: pointer;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* arrow kecil */
.arrow {
    font-size: 12px;
}

/* menu dropdown */
.dropdown-menu {
    position: absolute;
    top: 150%;
    left: -60px;
    min-width: 100px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 6px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    display: none;
    overflow: hidden;
    z-index: 100;
}

/* item */
.dropdown-item {
    width: 10%;
    border: none;
    background: white;
    text-align: left;
    cursor: pointer;
    transition: 0.2s;
}

.dropdown-item:hover {
    background: #f3f3f3;
}
</style>

@extends('layouts.admin')

@section('title','Tiket - Ticketflow')

@section('content')
    <section>
        <section class="top-content d-flex justify-content-between">
            <section>
                <input type="text" placeholder="Cari No Tiket..." id="search_ticket_no" name="search_ticket_no" class="input-search" style="text-indent: 10px">
                <input type="text" placeholder="Cari Judul Tiket..." id="search_ticket_title" name="search_ticket_title" class="input-search" style="text-indent: 10px">
                <select name="search_status" id="search_status" aria-placeholder="Filter Status" class="h-100 bg-transparent rounded" style="border: 1.5px solid #c1c1c1; color: #7c7c7c;">
                    <option value="">Filter Status</option>
                    <option value="pending">Menunggu Proses</option>
                    <option value="on_progress">Sedang Dikerjakan</option>
                    <option value="completed">Umpan Balik</option>
                    <option value="reject">Tolak</option>
                </select>
                <button
                    type="button"
                    id="refresh"
                    name="refresh"
                    class="rounded btn-refresh"
                    data-toggle="tooltip"
                    data-placement="bottom"
                    title="Muat Ulang"
                ><i class="fa-solid fa-arrows-rotate"></i></button>
            </section>
        </section>
        <section class="content-body">
            <table id="datatable" class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>No Ticket</th>
                        <th>Judul Ticket</th>
                        <th>Status</th>
                        <th>Pengguna</th>
                        <th>PIC</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </section>
    </section>

    {{-- START: Modal Detail --}}
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fw-bold" id="exampleModalLabel">Detail Tiket</h4>
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
                    
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary border-0"
                        data-bs-dismiss="modal"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Tutup"
                    >Tutup</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Modal Detail --}}

    {{-- START: Modal Delete --}}
    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fw-bold" id="modalDeleteLabel">Konfirmasi Hapus</h4>
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
                        <p>Tiket akan dihapus secara permanen dan tidak dapat dipulihkan.</p>
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

    {{-- START: Modal Assign --}}
    <div class="modal fade" id="modalAssign" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex flex-column">
                        <h4 class="modal-title p-0 fw-bold" id="modalTitleLabel"></h4>
                        <p class="p-0" id="modal-title-sub"></p>
                    </div>
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
                    <form id="formAssign" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="flag" id="flag">
                        <div class="d-flex flex-column gap-3">
                            <div class="form-group d-flex flex-column gap-1">
                                <label for="">PIC <span style="color: red;">*</span></label>
                                <select name="pic" id="pic" aria-placeholder="Pilih PIC" class="form-control" required>
                                    <option value="">Pilih PIC</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user['id'] }}">{{ $user['username'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group d-flex flex-column gap-1">
                                <label for="">Prioritas <span style="color: red;">*</span></label>
                                <select name="priority" id="priority" aria-placeholder="Pilih Prioritas" class="form-control" required>
                                    <option value="">Pilih Prioritas</option>
                                    <option value="low">Low</option>
                                    <option value="mid">Mid</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="form-group d-flex flex-column gap-1">
                                <label for="">Estimasi <span style="color: red;">*</span></label>
                                <input type="date" name="estimate" id="estimate" placeholder="Masukkan estimasi" class="form-control" required>
                            </div>
                            <div class="modal-footer">
                                <button
                                    type="button"
                                    class="btn btn-secondary border-0"
                                    data-bs-dismiss="modal"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Batal"
                                >Batal</button>
                                <button
                                    type="submit"
                                    class="btn btn-primary border-0"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Ambil Tiket"
                                >Ambil Tiket</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Modal Assign --}}

    {{-- START: Modal Reject --}}
    <div class="modal fade" id="modalReject" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex flex-column">
                        <div class="d-flex flex-column">
                            <h4 class="modal-title p-0 fw-bold" id="modalTitleLabel">Tolak Tiket</h4>
                            <p class="p-0" id="modal-title-sub"></p>
                        </div>
                    </div>
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
                    <form id="formReject" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group d-flex flex-column gap-1">
                            <label for="">Alasan Tolak <span style="color: red;">*</span></label>
                            <input type="text" name="reason" id="reason" placeholder="Masukkan alasan tolak" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary border-0"
                                data-bs-dismiss="modal"
                                data-toggle="tooltip"
                                data-placement="bottom"
                                title="Batal"
                            >Batal</button>
                            <button
                                type="submit"
                                class="btn btn-danger border-0"
                                data-toggle="tooltip"
                                data-placement="bottom"
                                title="Tolak"
                            >Tolak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Modal Reject --}}

    {{-- START: Modal Feedback --}}
    <div class="modal fade" id="modalFeedback" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex flex-column">
                        <div class="d-flex flex-column">
                            <h4 class="modal-title p-0 fw-bold" id="modalTitleLabel">Feedback</h4>
                            <p class="p-0" id="modal-title-sub"></p>
                        </div>
                    </div>
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
                    <form id="formFeedback" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="add_documentation" value="0">
                        <div class="d-flex flex-column gap-3">
                            <div class="form-group d-flex flex-column gap-1">
                                <label for="">Umpan Balik <span style="color: red;">*</span></label>
                                <input type="text" name="feedback" id="feedback" placeholder="Masukkan umpan balik" class="form-control" required>
                            </div>
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" name="add_documentation" id="add_documentation" value="1">
                                <label for="">Tambahkan ke documentasi ?</label>
                            </div>
                            <div class="modal-footer">
                                <button
                                    type="button"
                                    class="btn btn-secondary border-0"
                                    data-bs-dismiss="modal"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Batal"
                                >Batal</button>
                                <button
                                    type="submit"
                                    class="btn btn-primary border-0"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="Umpan Balik"
                                >Umpan Balik</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Modal Feedback --}}

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
        $('#search_ticket_no').val(null);
        $('#search_ticket_title').val(null);
        $('#search_status').val(null);

        const toast = new bootstrap.Toast(
            document.getElementById('liveToast')
        );

        const modalDetail = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalDetail')
        );

        const modalDelete = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalDelete')
        );

        const modalAssign = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalAssign')
        );

        const modalReject = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalReject')
        );

        const modalFeedback = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalFeedback')
        );
        // END: Init

        // START: Datatable
        let table = new DataTable('#datatable', {
            responsive: true,
            serverSide: true,
            ordering: false,
            ajax: {
                url: "{{ route('admin.pages.ticket.datatable') }}",
                data: function(d) {
                    d.ticket_no = $('#search_ticket_no').val();
                    d.ticket_title = $('#search_ticket_title').val();
                    d.status = $('#search_status').val();
                }
            },
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false },
                { data: "tanggal", name: "tanggal", searchable: false },
                { data: "ticket_no", name: "ticket_no", searchable: false },
                { data: "ticket_title", name: "ticket_title", searchable: false },
                { data: "status", name: "status", searchable: false },
                { data: "pengguna", name: "pengguna", searchable: false },
                { data: "pic", name: "pic", searchable: false },
                { data: "actions", name: "actions", orderable: false, searchable: false }
            ],
            columnDefs: [
                { orderable: false, targets: "_all" },
                { width: "10%", targets: 7 },
            ],
            dom: "t<'row mt-3'<'col-md-4'i><'col-md-4 text-center'p><'col-md-4 text-end'l>>",
        });
        // END: Datatable

        // START: Dropdown button functional
        document.addEventListener("click", function (e) {
            const btn = e.target.closest(".js-dropdown-btn");

            if (btn) {
                const menu = btn.nextElementSibling;

                document.querySelectorAll(".dropdown-menu").forEach(m => {
                    if (m !== menu) m.style.display = "none";
                });

                menu.style.display = menu.style.display === "block" ? "none" : "block";
                e.stopPropagation();

                return;
            }

            document.querySelectorAll(".dropdown-menu").forEach(m => {
                m.style.display = "none";
            });
        });
        // END: Dropdown button functional

        // START: Event Button Detail
        $(document).on('click', '.btn-detail', function() {
            const ticket_no = $(this).data('ticket');
            let url = "{{ route('admin.pages.ticket.detail', ':ticket_no') }}";
            url = url.replace(":ticket_no", ticket_no);

            $('#modalDetail .modal-body').html('');
            $.ajax({
                url: url,
                type: "GET",
                success: function(res) {
                    let attachment = '<p>Tidak ada lampiran.</p>'
                    if(res.ticket_file) {
                        attachment = `
                            <img src="/storage/${res.ticket_file.file_path}" class="img-fluid rounded">
                        `;
                    }

                    let html = `
                        <h4 class="fw-bold">Informasi</h4>
                        <table class="table table-sm table-borderless" style="border-collapse: separate; border-spacing: 0 0;">
                            <tr>
                                <td class="w-25">No Tiket</td>
                                <td style="width: 3%;">:</td>
                                <td>`+res.ticket_no+`</td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>:</td>
                                <td>`+res.report_date+`</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>`+(res.status_ticket.charAt(0).toUpperCase() + res.status_ticket.slice(1))+`</td>
                            </tr>
                            <tr>
                                <td>Prioritas</td>
                                <td>:</td>
                                <td>`+(res.priority ? res.priority.toUpperCase() : '-')+`</td>
                            </tr>
                            <tr>
                                <td>Estimasi</td>
                                <td>:</td>
                                <td>`+(res.estimate ?? '-')+`</td>
                            </tr>
                        </table>

                        <h4 class="mt-3 fw-bold">Dilaporkan Oleh</h4>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="w-25">Pengguna</td>
                                <td style="width: 3%;">:</td>
                                <td>`+res.member_name+`</td>
                            </tr>
                            <tr>
                                <td>No Whatsapp</td>
                                <td>:</td>
                                <td>`+res.no_wa+`</td>
                            </tr>
                            <tr>
                                <td>Departemen</td>
                                <td>:</td>
                                <td>`+res.department_name+`</td>
                            </tr>
                            <tr>
                                <td>Lokasi</td>
                                <td>:</td>
                                <td>`+res.location+`</td>
                            </tr>
                        </table>

                        <h4 class="mt-3 fw-bold">Addtional</h4>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="w-25">Judul Tiket</td>
                                <td style="width: 3%;">:</td>
                                <td>`+res.ticket_title+`</td>
                            </tr>
                            <tr>
                                <td>kategori</td>
                                <td>:</td>
                                <td>`+res.category_name+`</td>
                            </tr>
                            <tr>
                                <td>PIC</td>
                                <td>:</td>
                                <td>`+(res.users_name)+`</td>
                            </tr>
                            <tr>
                                <td>Kendala</td>
                                <td>:</td>
                                <td>`+res.problem+`</td>
                            </tr>
                            <tr>
                                <td>Catatan Pengguna</td>
                                <td>:</td>
                                <td>`+(res.note ?? '-')+`</td>
                            </tr>
                        </table>

                        <h4 class="mt-3 fw-bold">Lampiran</h4>
                        `+attachment+`

                        <h4 class="mt-3 fw-bold">Penilaian</h4>
                        <p>Tidak ada penilaian</p>
                    `;

                    $('#modalDetail .modal-body').html(html);
                }
            });

            modalDetail.show();
        });
        // END: Event Button Detail

        // START: Event Button Delete
        let deleteUrl = null;
        $(document).on('click', '.btn-remove', function() {
            deleteUrl = $(this).data('url');
            modalDelete.show();
        });

        $(document).on('click', '.btn-delete-modal', function() {
            $.ajax({
                url: deleteUrl,
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
        // END: Event Button Delete

        // START: Event Button Assign
        let formUrl = null;
        $(document).on('click', '.btn-assign', function() {
            formUrl = $(this).data('url');
            let ticketNo = $(this).data('ticket');
            
            $('#modalAssign #modalTitleLabel').text('Ambil Tiket');
            $('#modalAssign #modal-title-sub').text('#' + ticketNo);
            $('#pic').val(null);
            $('#priority').val(null);
            $('#estimate').val(null);
            $('#flag').val('assign');

            modalAssign.show();
        });

        $(document).on('submit', '#formAssign', function(e) {
            e.preventDefault();

            $.ajax({
                url: formUrl,
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(res) {
                    if(res.status == true) {
                        $('#toastTitle').text("Berhasil");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);

                        toast.show();
                        table.ajax.reload();
                        modalAssign.hide();
                        $('#formAssign')[0].reset();
                    } else {
                        $('#toastTitle').text("Gagal");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                        toast.show();
                    }
                }
            });
        });
        // END: Event Button Assign

        // START: Event Button Re Assign
        let reAssignUrl = null;
        $(document).on('click', '.btn-re-assign', function() {
            reAssignUrl = $(this).data('url');
            let ticketNo = $(this).data('ticket');
            
            $('#modalAssign #modalTitleLabel').text('Tugaskan Ulang Tiket');
            $('#modalAssign #modal-title-sub').text('#' + ticketNo);
            $('#pic').val(null);
            $('#priority').val(null);
            $('#estimate').val(null);
            $('#flag').val('re-assign');
            
            modalAssign.show();
        });

        $(document).on('submit', '#formAssign', function(e) {
            e.preventDefault();

            $.ajax({
                url: reAssignUrl,
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(res) {
                    if(res.status == true) {
                        $('#toastTitle').text("Berhasil");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);

                        toast.show();
                        table.ajax.reload();
                        modalAssign.hide();
                        $('#formAssign')[0].reset();
                    } else {
                        $('#toastTitle').text("Gagal");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                        toast.show();
                    }
                }
            });
        });
        // END: Event Button Re Assign

        // START: Event Button Reject
        let rejectUrl = null;
        $(document).on('click', '.btn-reject', function() {
            rejectUrl = $(this).data('url');
            let ticketNo = $(this).data('ticket');

            $('#reason').val(null);
            $('#modalReject #modal-title-sub').text('#' + ticketNo);

            modalReject.show();
        });

        $(document).on('submit', '#formReject', function(e) {
            e.preventDefault();

            $.ajax({
                url: rejectUrl,
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(res) {
                    if(res.status == true) {
                        $('#toastTitle').text("Berhasil");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);

                        toast.show();
                        table.ajax.reload();
                        modalReject.hide();
                        $('#formReject')[0].reset();
                    } else {
                        $('#toastTitle').text("Gagal");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                        toast.show();
                    }
                }
            })
        });
        // END: Event Button Reject

        // START: Event Button Feedback
        let feedbackUrl = null;
        $(document).on('click', '.btn-feedback', function() {
            feedbackUrl = $(this).data('url');
            let ticketNo = $(this).data('ticket');

            $('#modalFeedback #modal-title-sub').text('#' + ticketNo);
            $('#feedback').val(null);
            $('#add_documentation').prop('checked', false);

            modalFeedback.show();
        });

        $(document).on('submit', '#formFeedback', function(e) {
            e.preventDefault();

            $.ajax({
                url: feedbackUrl,
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(res) {
                    if(res.status == true) {
                        $('#toastTitle').text("Berhasil");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);

                        toast.show();
                        table.ajax.reload();
                        modalFeedback.hide();
                        $('#formFeedback')[0].reset();
                    } else {
                        $('#toastTitle').text("Gagal");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                        toast.show();
                    }
                }
            });
        });
        // END: Event Button Feedback

        // START: Filter & Refresh
        $(document).on('keyup click change', '#search_ticket_no, #search_ticket_title, #search_status', function() {
            table.ajax.reload();
        });
        // END: Filter & Refresh
    });
</script>

@endsection