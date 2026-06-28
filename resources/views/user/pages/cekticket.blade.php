<link rel="stylesheet" href="{{ asset('css/user/user.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@extends('layouts.user')

@section('title', 'Buat Tiket')

@section('content')

<div class=" row cekticket-header px-4">

    <h2 class="fw-bold mb-1">Cek Status Tiket</h2>
    <p class="text-muted mb-3">
        Cek tiket anda untuk melihat status dan perkembangan penanganan tiket.
    </p>

    <div class="row g-2">

        <div class="col-md-3">
            <input
                type="text"
                id="search_ticket_no"
                class="form-control ticket-input"
                placeholder="Cari nomor tiket..."
                value=""
            >
        </div>

        <div class="col-md-3">
            <input
                type="text"
                id="search_member"
                class="form-control ticket-input"
                placeholder="Cari nama pengguna..."
                value=""
            >
        </div>

        <div class="col-md-3">
            <input
                type="date"
                id="search_first_date"
                class="form-control ticket-input"
            >
        </div>

        <div class="col-md-3">
            <input
                type="date"
                id="search_end_date"
                class="form-control ticket-input"
            >
        </div>

    </div>

    <hr class="mt-3">

    <div class="list-tickets" id="list-tickets">
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
                <div class="modal-body p-5">
                    
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

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        // START: Init
        $('#search_ticket_no').val(null);
        $('#search_member').val(null);
        $('#search_first_date').val(null);
        $('#search_end_date').val(null);

        const today = new Date();
        const year = today.getFullYear();
        // Months are 0-indexed in JS (Jan = 0), so add 1 and pad with a leading zero
        const month = String(today.getMonth() + 1).padStart(2, '0'); 
        const firstDay = '01'; 

        // Combine into YYYY-MM-DD format
        document.getElementById('search_first_date').value = `${year}-${month}-${firstDay}`;

        const modalDetail = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalDetail')
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

        // START: Filter
        function loadTickets() {
            $.ajax({
                url: '{{ route("cek_status.filter") }}',
                data: {
                    ticket_no: $('#search_ticket_no').val(),
                    member_name: $('#search_member').val(),
                    first_date: $('#search_first_date').val(),
                    end_date: $('#search_end_date').val()
                },
                success: function(res) {
                    $('#list-tickets').html(res);
                }
            });
        }

        $('#search_ticket_no').on('keyup', function() {
            loadTickets();
        });

        $('#search_member').on('keyup', function() {
            loadTickets();
        });

        $("#search_first_date").on('change', function() {
            loadTickets();
        });

        $('#search_end_date').on('change', function() {
            loadTickets();
        });
        // END: Filter
    });
</script>
@endsection