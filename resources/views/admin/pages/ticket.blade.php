<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

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
    padding: 10px;
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

@section('title','ticket')

@section('content')
    <section>
        <section class="top-content d-flex justify-content-between">
            <section>
                <input type="text" placeholder="Search..." id="search" name="search" class="input-search" style="text-indent: 10px">
                <button type="button" id="refresh" name="refresh" class="btn-refresh"><i class="fa-solid fa-arrows-rotate"></i></button>
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
                        <th>Actions</th>
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
                    <h5 class="modal-title" id="exampleModalLabel">Detail Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    <h1 class="modal-title fs-5" id="modalDeleteLabel"><i class="fa-solid fa-triangle-exclamation" style="font-size: 1.5rem; color: orange;"></i> Are You Sure?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <center>
                        <p>Ticket cannot be retrieve after deleted.</p>
                    </center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn-delete-modal">Delete</button>
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
                        <h5 class="modal-title p-0" id="modalTitleLabel"></h5>
                        <p class="p-0" id="modal-title-sub"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAssign" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="flag" id="flag">
                        <div class="d-flex flex-column gap-3">
                            <div class="form-group">
                                <label for="">PIC <span style="color: red;">*</span></label>
                                <select name="pic" id="pic" aria-placeholder="Pilih PIC" class="form-control" required>
                                    <option value="">Pilih PIC</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user['id'] }}">{{ $user['username'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Prioritas <span style="color: red;">*</span></label>
                                <select name="priority" id="priority" aria-placeholder="Pilih Prioritas" class="form-control" required>
                                    <option value="">Pilih Prioritas</option>
                                    <option value="low">Low</option>
                                    <option value="mid">Mid</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Estimasi <span style="color: red;">*</span></label>
                                <input type="date" name="estimate" id="estimate" placeholder="Masukkan estimasi" class="form-control" required>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Assign</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                        <div class="d-flex flex-column gap-3">
                            <h5 class="modal-title p-0" id="modalTitleLabel">Tolak Tiket</h5>
                            <p class="p-0" id="modal-title-sub"></p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formReject" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="">Alasan Tolak <span style="color: red;">*</span></label>
                            <input type="text" name="reason" id="reason" placeholder="Masukkan alasan tolak" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Tolak</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                        <div class="d-flex flex-column gap-3">
                            <h5 class="modal-title p-0" id="modalTitleLabel">Feedback</h5>
                            <p class="p-0" id="modal-title-sub"></p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formFeedback" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="add_documentation" value="0">
                        <div class="d flex flex-column gap-3">
                            <div class="form-group">
                                <label for="">Umpan Balik <span style="color: red;">*</span></label>
                                <input type="text" name="feedback" id="feedback" placeholder="Masukkan umpan balik" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="add_documentation" id="add_documentation" value="1">
                                <label for="">Tambahkan ke documentasi ?</label>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Umpan Balik</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        // START: Init
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
            layout: {
                topStart: null,
                topEnd: null,

                bottomStart: 'info',
                bottomEnd: 'pageLength'
            },
            ajax: "{{ route('admin.pages.ticket.datatable') }}",
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
                // { width: "5%", className: "dt-center", targets: 0 },
                // { className: "dt-left", targets: 1 }, 
                // { width: "20%", className: "dt-right", targets: 2 }
            ]
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
                                <td>`+(res.priority.toUpperCase() ?? '-')+`</td>
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
                        <p>Tidak ada lampiran</p>

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
        $(document).on('click', '.btn-remove', function() {
            let url = $(this).data('url');
            modalDelete.show();

            $(document).on('click', '.btn-delete-modal', function() {
                $.ajax({
                    url: url,
                    type: "DELETE",
                    success: function(res) {
                        if(res.status == true) {
                            $('#toastTitle').text("Success");
                            $('#toastBody').text(res.message);
                            $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);
                            toast.show();
    
                            table.ajax.reload();
                            modalDelete.hide();
                        } else {
                            $('#toastTitle').text("Error");
                            $('#toastBody').text(res.message);
                            $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                            toast.show();
                        }
                    }
                });
            });
        });
        // END: Event Button Delete

        // START: Event Button Assign
        $(document).on('click', '.btn-assign', function() {
            let url = $(this).data('url');
            let ticketNo = $(this).data('ticket');
            
            $('#modalAssign #modalTitleLabel').text('Assign Ticket');
            $('#modalAssign #modal-title-sub').text('#' + ticketNo);
            $('#pic').val(null);
            $('#priority').val(null);
            $('#estimate').val(null);
            $('#flag').val('assign');

            $(document).on('submit', '#formAssign', function(e) {
                e.preventDefault();

                $.ajax({
                    url: url,
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if(res.status == true) {
                            $('#toastTitle').text("Success");
                            $('#toastBody').text(res.message);
                            $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);
                            toast.show();

                            table.ajax.reload();
                            modalAssign.hide();
                            $(this)[0].reset();
                        } else {
                            $('#toastTitle').text("Error");
                            $('#toastBody').text(res.message);
                            $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                            toast.show();
                        }
                    }
                });
            });
            
            modalAssign.show();
        });
        // END: Event Button Assign

        // START: Event Button Re Assign
        $(document).on('click', '.btn-re-assign', function() {
            let url = $(this).data('url');
            let ticketNo = $(this).data('ticket');
            
            $('#modalAssign #modalTitleLabel').text('Re-assign Ticket');
            $('#modalAssign #modal-title-sub').text('#' + ticketNo);
            $('#pic').val(null);
            $('#priority').val(null);
            $('#estimate').val(null);
            $('#flag').val('re-assign');

            $(document).on('submit', '#formAssign', function(e) {
                e.preventDefault();

                $.ajax({
                    url: url,
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if(res.status == true) {
                            $('#toastTitle').text("Success");
                            $('#toastBody').text(res.message);
                            $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);
                            toast.show();

                            table.ajax.reload();
                            modalAssign.hide();
                            $(this)[0].reset();
                        } else {
                            $('#toastTitle').text("Error");
                            $('#toastBody').text(res.message);
                            $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                            toast.show();
                        }
                    }
                });
            });
            
            modalAssign.show();
        });
        // END: Event Button Re Assign

        // START: Event Button Reject
        $(document).on('click', '.btn-reject', function() {
            let url = $(this).data('url');
            let ticketNo = $(this).data('ticket');

            $('#reason').val(null);
            $('#modalReject #modal-title-sub').text('#' + ticketNo);

            $(document).on('submit', '#formReject', function(e) {
                e.preventDefault();

                $.ajax({
                    url: url,
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if(res.status == true) {
                            $('#toastTitle').text("Success");
                            $('#toastBody').text(res.message);
                            $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);
                            toast.show();

                            table.ajax.reload();
                            modalReject.hide();
                            $(this)[0].reset();
                        } else {
                            $('#toastTitle').text("Error");
                            $('#toastBody').text(res.message);
                            $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                            toast.show();
                        }
                    }
                })
            });

            modalReject.show();
        });
        // END: Event Button Reject

        // START: Event Button Feedback
        $(document).on('click', '.btn-feedback', function() {
            let url = $(this).data('url');
            let ticketNo = $(this).data('ticket');

            $('#modalFeedback #modal-title-sub').text('#' + ticketNo);
            $('#feedback').val(null);
            $('#add_documentation').prop('checked', false);

            $(document).on('submit', '#formFeedback', function(e) {
                e.preventDefault();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if(res.status == true) {
                            $('#toastTitle').text("Success");
                            $('#toastBody').text(res.message);
                            $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);
                            toast.show();

                            table.ajax.reload();
                            modalFeedback.hide();
                            $(this)[0].reset();
                        } else {
                            $('#toastTitle').text("Error");
                            $('#toastBody').text(res.message);
                            $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                            toast.show();
                        }
                    }
                });
            });

            modalFeedback.show();
        });
        // END" Event Button Feedback
    });
</script>

@endsection