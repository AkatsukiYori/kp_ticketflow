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
                <div class="d-flex gap-1">
                    <select id="month" aria-placeholder="Semua Bulan" class="form-control" style="width: 15%;">
                        <option value="">Semua Bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">juni</option>
                        <option value="7">July</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                    <select id="year" aria-placeholder="Semua Tahun" class="form-control" style="width: 15%;">
                        <option value="">Semua Tahun</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-secondary" id="btn-filter">Filter</button>
                </div>
            </div>
            <div class="container">
                <div class="row row-cols-2">
                    <div class="horizontal-bar-chart-section col-7 mt-4 px-0 d-flex flex-column align-items-center" id="horizontal-bar-chart-section">
                        <h4 class="m-0 fs-4 fw-bold">Kategori</h4>
                    </div>
                    <div class="pie-chart-section col-5 d-flex flex-column align-items-center justify-content-start gap-3" id="pie-chart-section">
                        <h4 class="m-0 fs-4 fw-bold">Prioritas</h4>
                    </div>
                    <div class="ticket-table col-7" id="ticket-table">
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
                    <div class="col-5">
                        <div class="rating border border-1 rounded p-3" id="rating">
                            <h4 class="m-0 fs-4 fw-bold">Rating</h4>
                            <div class="rating-content mt-3 d-flex flex-column gap-2" id="rating-content"></div>
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
    let categoryChart = null;
    let priorityChart = null;

    function loadDashboard() {
        $.ajax({
            url: "{{ route('admin.pages.dashboard.filter') }}",
            type: 'GET',
            data: {
                month: $('#month').val(),
                year: $('#year').val()
            },
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

                if(categoryChart) {
                    categoryChart.destroy();
                }

                categoryChart = new ApexCharts(document.querySelector('#horizontal-bar-chart-section'), optionsBar);
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

                if(priorityChart) {
                    priorityChart.destroy();
                }

                priorityChart = new ApexCharts(document.querySelector('#pie-chart-section'), optionsPie)
                priorityChart.render();
                // END: Priority Chart

                // START: Rating
                let html = '';
                res.ratingCounter.forEach(element => {
                    // console.log(element.label);
                    html += `
                        <div id="progressbar-item" class="d-flex justify-content-between align-items-center">
                            <p class="m-0" style="width: 15%;">`+element.label+` : </p>
                            <div class="progress bg-teal-500 border border-1 rounded" style="width: 80%; height: 20px;">
                                <div class="progress-bar rounded rating-bar" style="width: 0; transition: width .8 ease;"></div>
                            </div>
                            <p class="m-0 text-center align-middle" style="width: 10%;">(`+element.count+`)</p>
                        </div>
                    `;
                    
                });
                setTimeout(() => {
                    $('.rating-bar').each(function(index) {
                        $(this).css('width', res.ratingCounter[index].percentage + '%');
                    });
                }, 50);
                $('#rating-content').html(html);
                // END: Rating
            }
        })
    }
    // END: First load

    $(document).ready(function() {
        // START: Init
        $('#month').val('');
        $('#year').val('');
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
                url: "{{ route('admin.pages.dashboard.datatable') }}",
                data: function(d) {
                    d.month = $('#month').val();
                    d.year = $('#year').val();
                }
            },
            columns: [
                { data: 'ticket_no', name: 'ticket_no', orderable: false, searchable: false },
                { data: 'ticket_title', name: 'ticket_title', orderable: false, searchable: false },
                { data: 'date', name: 'date', orderable: false, searchable: false },
                { data: 'status', name: 'status', orderable: false, searchable: false }
            ],
            dom: "t<'row mt-3'<'col-md-6 text-center'p><'col-md-6 text-end'l>>",
        });
        // END: Datatable

        // START: Filter
        $('#btn-filter').on('click', function() {
            loadDashboard();
            table.ajax.reload();
        });
        // END: Filter
    });
</script>
@endsection