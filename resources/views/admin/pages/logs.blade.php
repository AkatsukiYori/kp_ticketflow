<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

@extends('layouts.admin')

@section('title','logs')

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
                        <th>Pengguna</th>
                        <th>No Tiket</th>
                        <th>Judul Tiket</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Actions</th>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="timeline" class="d-flex flex-row gap-3 align-items-start justify-content-start">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END: Detail Modal --}}
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        const modal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalLogs')
        );

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
            ajax: "{{ route('admin.pages.logs.datatable') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'pengguna', name: 'pengguna', orderable: false, searchable: false },
                { data: 'no_ticket', name: 'no_ticket', orderable: false, searchable: false },
                { data: 'judul_ticket', name: 'judul_ticket', orderable: false, searchable: false },
                { data: 'tanggal', name: 'tanggal', orderable: false, searchable: false },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ]
        });

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
                            <div id="timeline-left" class="">
                                <div class="mt-2" style="background-color: grey; width: 10px; height: 10px; border-radius: 100%;"></div>
                            </div>
                            <div id="timeline-right" class="d-flex flex-column">
                                <div class="title">
                                    <p>
                                        <strong>`+ (item.action_type.charAt(0).toUpperCase() + item.action_type.slice(1)) +`</strong>
                                        By
                                        `+ item.created_by +`
                                        `+ formattedDate +`
                                    </p>
                                    <p>`+ (item.description ?? '') +`</p>
                                </div>
                                <div class="description"></div>
                            </div>
                        `;
                    });

                    $('#timeline').html(html);
                    modal.show();
                }
            });
        });
    });
</script>
@endsection