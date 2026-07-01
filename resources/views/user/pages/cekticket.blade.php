@extends('layouts.user')

@section('title', 'Buat Tiket - Ticketflow')

@section('content')

<div class="container-fluid px-4">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Cek Status Ticket</h2>
        <p class="text-muted mb-0">
            Cek ticket anda untuk melihat status dan perkembangan penanganan ticket.
        </p>
    </div>
    <div class="row g-3">

        <div class="col-lg-2">
            <input type="text"
                class="form-control"
                id="search_ticket_no"
                placeholder="Cari nomor tiket...">
        </div>

        <div class="col-lg-2">
            <input type="text"
                class="form-control"
                id="search_ticket_title"
                placeholder="Cari tiket...">
        </div>

        <div class="col-lg-2">
            <input type="text"
                class="form-control"
                id="search_member"
                placeholder="Cari nama pengguna...">
        </div>

        <div class="col-lg-2">
            <select
                id="search_status"
                class="form-control"
            >
                <option value="">Pilih Status</option>
                <option value="pending">Pending</option>
                <option value="on_progress">On Progress</option>
                <option value="completed">Completed</option>
                <option value="reject">Reject</option>
            </select>
        </div>

        <div class="col-lg-2">
            <input type="date"
                class="form-control"
                id="search_first_date">
        </div>

        <div class="col-lg-2">
            <input type="date"
                class="form-control"
                id="search_end_date">
        </div>

    </div>

    <hr>

    <div id="list-tickets">
        @include('partials.user.ticket_list')
    </div>
</div>

{{-- START: Modal Detail --}}
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Detail Tiket</h5>
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
                        class="btn btn-secondary"
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

{{-- START: Modal Log --}}
<div class="modal fade" id="modalLogs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex flex-column">
                    <h4 class="modal-title fs-4 fw-bold" id="modalLogsLabel">Logs</h4>
                    <h6 class="modal-subtitle"></h6>
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
                <div id="timeline" class="d-flex flex-column gap-5 align-items-start justify-content-start">
                    
                </div>
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
{{-- END: Modal Log --}}

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
                    <div class="d-flex flex-column gap-3">
                        <div class="form-group d-flex flex-column gap-1">
                            <label for="">Respon <span style="color: red;">*</span></label>
                            <input type="text" name="feedback" id="feedback" placeholder="Masukkan respon" class="form-control" required>
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
                                title="Respon"
                            >Respon</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END: Modal Feedback --}}

{{-- START: Modal Closed Ticket --}}
<div class="modal fade" id="modalCloseTicket" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title p-0 fw-bold" id="modalTitleLabel">Konfirmasi Tutup Tiket</h4>
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
                <p class="text-justify">Apakah anda yakin ingin menutup tiket <span id="ticket_no_span_close"></span> Pastikan kendala anda sudah teratasi sebelum menutup tiket. Dengan menutup tiket, ini menandai laporan anda sudah terselesaikan sepenuhnya.</p>
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
                    type="button"
                    class="btn btn-danger border-0"
                    id="submit-closed-ticket"
                    data-toggle="tooltip"
                    data-placement="bottom"
                    title="Tutup Tiket"
                >Tutup Tiket</button>
            </div>
        </div>
    </div>
</div>
{{-- END: Modal Closed Ticket --}}

{{-- START: Modal Feedback --}}
<div class="modal fade" id="modalRating" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex flex-column">
                    <div class="d-flex flex-column">
                        <h4 class="modal-title p-0 fw-bold" id="modalTitleLabel">Rating</h4>
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
                <form id="formRating" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex flex-column gap-3">
                        <p>Berikan penilaian terhadap penanganan kasus anda.</p>
                        <div class="form-group d-flex flex-column gap-1">
                            <label for="">Point <span class="text-danger">*</span></label>
                            <div id="point-section" class="d-flex gap-2">
                                <span>1</span>
                                <span>2</span>
                                <span>3</span>
                                <span>4</span>
                                <span>5</span>
                            </div>
                        </div>
                        <div class="form-group d-flex flex-column gap-1">
                            <label for="">Catatan (Opsional)</label>
                            <input type="text" name="note" id="note" placeholder="Masukkan catatan" class="form-control">
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
                                title="Rating"
                            >Rating</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- END: Modal Feedback --}}

{{-- START: Modal Open Ticket --}}
<div class="modal fade" id="modalOpenTicket" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title p-0 fw-bold" id="modalTitleLabel">Konfirmasi Buka Tiket</h4>
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
                <p class="text-justify">Apakah anda yakin ingin buka tiket #<span id="ticket_no_span_open"></span> ?</p>
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
                    type="button"
                    class="btn btn-primary border-0"
                    id="submit-open-ticket"
                    data-toggle="tooltip"
                    data-placement="bottom"
                    title="Buka Tiket"
                >Buka Tiket</button>
            </div>
        </div>
    </div>
</div>
{{-- END: Modal Open Ticket --}}

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
    function loadTickets() {
        $.ajax({
            url: '{{ route("cek_status.filter") }}',
            data: {
                ticket_no: $('#search_ticket_no').val(),
                ticket_title: $('#search_ticket_title').val(),
                member_name: $('#search_member').val(),
                status: $('#search_status').val(),
                first_date: $('#search_first_date').val(),
                end_date: $('#search_end_date').val()
            },
            success: function(res) {
                $('#list-tickets').html(res);
            }
        });
    }

    $(document).ready(function() {
        // START: Init
        $('#search_ticket_no').val(null);
        $('#search_ticket_title').val(null);
        $('#search_member').val(null);
        $('#search_status').val(null);
        $('#search_first_date').val(null);
        $('#search_end_date').val(null);

        const today = new Date();
        const year = today.getFullYear();
        // Months are 0-indexed in JS (Jan = 0), so add 1 and pad with a leading zero
        const month = String(today.getMonth() + 1).padStart(2, '0'); 
        const firstDay = '01'; 

        // Combine into YYYY-MM-DD format
        document.getElementById('search_first_date').value = `${year}-${month}-${firstDay}`;

        const toast = new bootstrap.Toast(
            document.getElementById('liveToast')
        );

        const modalDetail = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalDetail')
        );

        const modalLog = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalLogs')
        );

        const modalFeedback = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalFeedback')
        );

        const modalCloseTicket = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalCloseTicket')
        );

        const modalRating = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalRating')
        );

        const modalOpenTicket = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalOpenTicket')
        );
        // END: Init

        // START: Event Button Detail
        $(document).on('click', '.btn-detail', function() {
            const ticket_no = $(this).data('ticket');
            let url = "{{ route('cek_status.detail', ':ticket_no') }}";
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

        // START: Event Button Log
        $(document).on('click', '.btn-log', function() {
            let url = $(this).data('url');

            $.ajax({
                url: url,
                type: "GET",
                success: function(res) {
                    let html = '';
                    var formatter = new Intl.DateTimeFormat('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });

                    $.each(res.logs, function(index, item) {
                        var rawDate = new Date(item.log_date);
                        var formattedDate = formatter.format(rawDate).replace('.', ':').replace(' pukul', ',').toLowerCase();
                        html += `
                            <div class="timeline-item d-flex gap-3 align-items-center">
                                <div class="timeline-left">
                                    <div class="timeline-dot"></div>
                                </div>
                                <div class="timeline-right">
                                    <p class="m-0">
                                        <strong>`+ (item.action_type.charAt(0).toUpperCase() + item.action_type.slice(1)) +`</strong>
                                        By
                                        `+ item.created_by +`
                                        `+ formattedDate +`
                                    </p>
                                    <p>`+ (item.description ?? '') +`</p>
                                </div>
                            </div>
                        `;
                    });
                    $('#timeline').html(html);
                    $('.modal-subtitle').text('#' + res.ticket_no);

                    modalLog.show();
                }
            });
        });
        // END: Event Button Log

        // START: Event Button Feedback
        let feedbackUrl = null;
        $(document).on('click', '.btn-respon', function() {
            feedbackUrl = $(this).data('url');
            let ticketNo = $(this).data('ticket');

            $('#modalFeedback #modal-title-sub').text('#' + ticketNo);
            $('#feedback').val(null);

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

                        modalFeedback.hide();
                        toast.show();
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

        // START: Event Button Close Ticket
        let urlClosed = null;
        $(document).on('click', '.btn-close-ticket', function() {
            urlClosed = $(this).data('url');
            let ticketNo = $(this).data('ticket');
            $('#ticket_no_span_close').text('#' + ticketNo);            
            modalCloseTicket.show();
        });

        $(document).on('click', '#submit-closed-ticket', function() {
            $.ajax({
                url: urlClosed,
                type: 'POST',
                success: function(res) {
                    if(res.status == true) {
                        $('#toastTitle').text("Berhasil");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);

                        modalCloseTicket.hide();
                        toast.show();
                    } else {
                        $('#toastTitle').text("Gagal");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                        toast.show();
                    }
                }
            });
        });
        // END: Event Button Close Ticket

        // START: Event Button Rating
        let urlRating = null;
        let ratingPoint = 0;
        $(document).on('click', '.btn-rating', function() {
            urlRating = $(this).data('url');
            let ticketNo = $(this).data('ticket');
            $('#note').val(null);
            $('#modal-title-sub').text(ticketNo);
            modalRating.show();
        });

        $(document).on('click', '#point-section span', function() {
            $('#point-section span').removeClass('active');
            $(this).addClass('active');
            ratingPoint = $(this).text();
        });

        $(document).on('submit', '#formRating', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('score', ratingPoint);
            $.ajax({
                url: urlRating,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    if(res.status == true) {
                        $('#toastTitle').text("Berhasil");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);

                        modalRating.hide();
                        toast.show();
                    } else {
                        $('#toastTitle').text("Gagal");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                        toast.show();
                    }
                }
            })
        });
        // END: Event Button Rating

        // START: Event Button Open
        let urlOpenTicket = null;
        $(document).on('click', '.btn-open-ticket', function() {
            urlOpenTicket = $(this).data('url');
            let ticketNo = $(this).data('ticket');
            modalOpenTicket.show();
            $('#ticket_no_span_open').text(ticketNo);
        });

        $(document).on('click', '#submit-open-ticket', function() {
            $.ajax({
                url: urlOpenTicket,
                type: 'POST',
                success: function(res) {
                    if(res.status == true) {
                        $('#toastTitle').text("Berhasil");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);

                        modalOpenTicket.hide();
                        toast.show();
                    } else {
                        $('#toastTitle').text("Gagal");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                        toast.show();
                    }
                }
            });
        });
        // END: Event Button Open

        // START: Filter
        let timer;
        $('#search_ticket_no, #search_member, #search_ticket_title').on('keyup', function() {
            clearTimeout(timer);

            timer = setTimeout(loadTickets(), 400);
        });

        $("#search_first_date, #search_end_date, #search_status").on('change', function() {
            loadTickets();
        });
        // END: Filter
    });
</script>
@endsection