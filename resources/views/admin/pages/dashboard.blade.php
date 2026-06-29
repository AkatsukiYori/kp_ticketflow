@extends('layouts.admin')

@section('title','dashboard')

@section('content')
    <section class="px-3">
        <div class="top-content d-flex justify-content-around gap-3">
            <div class="card w-100 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-left">
                        <i class="fa-solid fa-border-all fs-2"></i>
                    </div>
                    <div class="card-body text-center">
                        <div class="d-flex flex-column">
                            <h3 class="fs-2 fw-bold">{{ $counter['all'] }}</h3>
                            <p>Total Tiket</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card w-100 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-left">
                        <i class="fa-solid fa-spinner fs-2"></i>
                    </div>
                    <div class="card-body text-center">
                        <div class="d-flex flex-column">
                            <h3 class="fs-2 fw-bold">{{ $counter['on_progress'] }}</h3>
                            <p>On Progress</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card w-100 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-left">
                        <i class="fa-regular fa-circle-check fs-2"></i>
                    </div>
                    <div class="card-body text-center">
                        <div class="d-flex flex-column">
                            <h3 class="fs-2 fw-bold">{{ $counter['closed'] }}</h3>
                            <p>Closed</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card w-100 px-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="card-left">
                        <i class="fa-regular fa-circle-xmark fs-2"></i>
                    </div>
                    <div class="card-body text-center">
                        <div class="d-flex flex-column">
                            <h3 class="fs-2 fw-bold">{{ $counter['reject'] }}</h3>
                            <p>Reject</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-content mt-4" id="dashboard-content">
            <div class="filter" id="filter">
                <h4 class="fs-4 fw-bold mb-2"> Filter</h4>
                <div class="d-flex gap-3">
                    <select id="month" aria-placeholder="Semua Bulan">
                        <option value="">Semua Bulan</option>
                    </select>
                    <select id="year" aria-placeholder="Semua Tahun">
                        <option value="">Semua Tahun</option>
                    </select>
                    <button type="button" class="btn btn-secondary">Filter</button>
                </div>
            </div>
            <div class="container">
                <div class="row row-cols-2">
                    <div class="horizontal-bar-chart-section col-6" id="horizontal-bar-chart-section"></div>
                    <div class="pie-chart-section col" id="pie-chart-section"></div>
                    <div class="ticket-table col-6" id="ticket-table">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Ticket No</th>
                                        <th>Ticket Title</th>
                                        <th>Report Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="col">
                        <p>hai</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // START: Datatable
        let table = new DataTable('#datatable', {
            responsive: true,
            serverSide: true,
            ordering: false,
            layout: {
                topStart: null,
                topEnd: null,
            },
            ajax: {
                url: "{{ route('admin.pages.dashboard.datatable') }}"
            },
            columns: [
                { data: 'ticket_no', name: 'ticket_no', orderable: false, searchable: false },
                { data: 'ticket_title', name: 'ticket_title', orderable: false, searchable: false },
                { data: 'date', name: 'date', orderable: false, searchable: false },
                { data: 'status', name: 'status', orderable: false, searchable: false }
            ]
        });
        // END: Datatable

        // START: BarChart
        const options = {
            chart: {
                type: 'bar'
            },
            series: [{
                data: [10, 20, 30]
            }],
            xaxis: {
                categories: ['A', 'B', 'C']
            }
        };

        new ApexCharts(document.querySelector('#horizontal-bar-chart-section'), options).render();
        // END: BarChart
    });
</script>
@endsection