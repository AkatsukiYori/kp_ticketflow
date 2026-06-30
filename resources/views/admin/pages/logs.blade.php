@extends('layouts.admin')

@section('title','Log - Ticketflow')

@section('content')
    <section>
        <section class="top-content d-flex justify-content-between">
            <section>
                <input type="text" placeholder="Cari No Tiket..." id="search" name="search" class="input-search" style="text-indent: 10px">
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
                        <th>Pengguna</th>
                        <th>No Tiket</th>
                        <th>Judul Tiket</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </section>
    </section>

    {{-- START: Detail Modal --}}
    <div class="modal fade" id="modalLogs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex flex-column">
                        <h5 class="modal-title" id="modalLogsLabel">Logs</h5>
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
    {{-- END: Detail Modal --}}
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // START: Init
        $('#search').val(null);

        const modal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalLogs')
        );
        // END: Init

        // START: DataTable
        let table = new DataTable('#datatable', {
            responsive: true,
            serverSide: true,
            ordering: false,
            ajax: {
                url: "{{ route('admin.pages.logs.datatable') }}",
                data: function(d) {
                    d.ticket_no = $('#search').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'pengguna', name: 'pengguna', orderable: false, searchable: false },
                { data: 'no_ticket', name: 'no_ticket', orderable: false, searchable: true },
                { data: 'judul_ticket', name: 'judul_ticket', orderable: false, searchable: false },
                { data: 'tanggal', name: 'tanggal', orderable: false, searchable: false },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            dom: "t<'row mt-3'<'col-md-4'i><'col-md-4 text-center'p><'col-md-4 text-end'l>>",
        });
        // END: DataTable

        // START: Event Button Detail
        $(document).on('click', '#btn-detail', function() {
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

                    $.each(res, function(index, item) {
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

                    modal.show();
                }
            });
        });
        // END: Event Button Detail

        // START: Filter & Refresh
        $(document).on('keyup click', '#search, #refresh', function() {
            table.ajax.reload();
        });
        // END: Filter & Refresh
    });
</script>
@endsection