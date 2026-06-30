@extends('layouts.admin')

@section('title','Dashboard - Ticketflow')

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
                            <h3 class="fs-2 fw-bold" id="total_ticket_counter_card"></h3>
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
                            <h3 class="fs-2 fw-bold" id="progress_ticket_counter_card"></h3>
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
                            <h3 class="fs-2 fw-bold" id="closed_ticket_counter_card"></h3>
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
                            <h3 class="fs-2 fw-bold" id="reject_ticket_counter_card"></h3>
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
                    <select id="month" aria-placeholder="Semua Bulan" class="form-control" style="width: 15%;">
                        <option value="">Semua Bulan</option>
                    </select>
                    <select id="year" aria-placeholder="Semua Tahun" class="form-control" style="width: 15%;">
                        <option value="">Semua Tahun</option>
                    </select>
                    <button type="button" class="btn btn-secondary">Filter</button>
                </div>
            </div>
            <div class="container">
                <div class="row row-cols-2">
                    <div class="horizontal-bar-chart-section col-6 mt-4 px-0" id="horizontal-bar-chart-section">
                        <h4 class="m-0 fs-4 fw-bold">Kategori</h4>
                    </div>
                    <div class="pie-chart-section col" id="pie-chart-section">
                        <h4 class="m-0 fs-4 fw-bold">Prioritas</h4>
                    </div>
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
                        <div class="rating border border-1 rounded p-3" id="rating">
                            <h4 class="m-0 fs-4 fw-bold">Rating</h4>
                            <div class="rating-content mt-3" id="rating-content">
                                <div id="progressbar-item" class="d-flex justify-content-between align-items-center">
                                    <p class="m-0" style="width: 15%;" id="progressbar-label">Score 5 : </p>
                                    <div class="progress bg-teal-500 border border-1 rounded" style="position: relative; width: 100%; height: 20px;">
                                        <div class="progress-bar rounded" style="position: absolute; background-color: darkblue; width: 50%; height: 100%; top: 0; left: 0"></div>
                                    </div>
                                    <p class="m-0 text-center align-middle" style="width: 10%;" id="progressbar-total">(0)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
<script>
    // START: First load
    function loadDashboard() {
        $.ajax({
            url: "{{ route('admin.pages.dashboard.filter') }}",
            type: 'GET',
            success: function(res) {
                console.log(res);
                $('#total_ticket_counter_card').text(res.counter.all);
                $('#progress_ticket_counter_card').text(res.counter.on_progress);
                $('#reject_ticket_counter_card').text(res.counter.reject);
                $('#closed_ticket_counter_card').text(res.counter.closed);

                // START: Category Chart
                const categoriesLabel = res.categoryCounter.map(item => item.label);
                const categoriesCounter = res.categoryCounter.map(item => item.count);

                const optionsBar = {
                    chart: {
                        type: 'bar'
                    },
                    series: [{
                        data: categoriesCounter
                    }],
                    xaxis: {
                        categories: categoriesLabel
                    }
                };

                const categoryChart = new ApexCharts(document.querySelector('#horizontal-bar-chart-section'), optionsBar);
                categoryChart.render();
                // END: Category Chart

                // START: Priority Chart
                const priorityLabel = res.priorityCounter.map(item => item.label);
                const priorityCounter = res.priorityCounter.map(item => item.count);

                var optionsPie = {
                    series: priorityCounter,
                    chart: {
                        width: 380,
                        type: 'donut',
                    },
                    labels: priorityLabel,
                    dataLabels: {
                        enabled: false,
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200,
                            },
                            legend: {
                                show: false,
                            },
                        },
                    }],
                    legend: {
                        position: 'right',
                        offsetY: 0,
                        height: 230,
                    },
                }

                const priorityChart = new ApexCharts(document.querySelector('#pie-chart-section'), optionsPie)
                priorityChart.render();
                // END: Priority Chart

                // START: Rating
                
                // END: Rating
            }
        })
    }
    // END: First load

    $(document).ready(function() {
        // START: Init
        loadDashboard();
        // END: Init

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
    });
</script>
@endsection