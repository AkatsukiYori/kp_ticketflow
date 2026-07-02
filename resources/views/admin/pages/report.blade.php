@extends('layouts.admin')

@section('title','Laporan & Statistik - Ticketflow')

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
                            <h3 class="fs-2 fw-bold" id="pending_ticket_counter_card"></h3>
                            <p>Pending</p>
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
                        @foreach ($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    <select id="search_category" aria-placeholder="Semua Kategori" class="form-control" style="width: 15%;">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-secondary" id="btn-filter">Filter</button>
                    <button type="button" class="btn btn-primary" id="btn-export">Export Excel</button>
                </div>
            </div>
            <div class="container">
                <div class="row row-cols-2">
                    <div class="horizontal-bar-chart-tickets-section col-7 mt-4 px-0 d-flex flex-column align-items-center" id="horizontal-bar-chart-ticket-section">
                        <h4 class="m-0 fs-4 fw-bold">Tickets</h4>
                    </div>
                     <div class="pie-chart-section col-5 d-flex flex-column align-items-center justify-content-start gap-3" id="pie-chart-section">
                        <h4 class="m-0 fs-4 fw-bold">Prioritas</h4>
                    </div>

                    <div class="horizontal-bar-chart-category-section col-7 mt-4 px-0 d-flex flex-column align-items-center" id="horizontal-bar-chart-category-section">
                        <h4 class="m-0 fs-4 fw-bold">Kategori</h4>
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
    let ticketChart = null;

    function loadDashboard() {
        $.ajax({
            url: "{{ route('admin.pages.report.filter') }}",
            type: 'GET',
            data: {
                month: $('#month').val(),
                year: $('#year').val(),
                category: $('#search_category').val()
            },
            success: function(res) {
                $('#total_ticket_counter_card').text(res.counter.all);
                $('#pending_ticket_counter_card').text(res.counter.pending);
                $('#progress_ticket_counter_card').text(res.counter.on_progress);
                $('#reject_ticket_counter_card').text(res.counter.reject);
                $('#closed_ticket_counter_card').text(res.counter.closed);

                // START: Tickets Chart
                
                const ticketLabel = res.ticketCounter.map(item => item.label);
                const ticketCounter = res.ticketCounter.map(item => item.count);

                const optionsBarTicket = {
                    chart: {
                        type: 'bar'
                    },
                    series: [{
                        data: ticketCounter
                    }],
                    xaxis: {
                        categories: ticketLabel
                    }
                };

                if(ticketChart) {
                    ticketChart.destroy();
                }

                ticketChart = new ApexCharts(document.querySelector('#horizontal-bar-chart-ticket-section'), optionsBarTicket);
                ticketChart.render();
                // END: Tickets Chart

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

                categoryChart = new ApexCharts(document.querySelector('#horizontal-bar-chart-category-section'), optionsBar);
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
        let tahunSekarang = new Date().getFullYear();
        $('#year').val(tahunSekarang);
        $('#month').val('');
        $('#search_category').val('');

        loadDashboard();
        // END: Init

        // START: Filter
        $('#btn-filter').on('click', function() {
            loadDashboard();
            table.ajax.reload();
        });
        // END: Filter

        // START: Export
        $(document).on('click', '#btn-export', function() {
            const params = new URLSearchParams({
                month: $('#month').val(),
                year: $('#year').val(),
                category: $('#search_category').val()
            });

            window.open(
                "{{ route('admin.pages.report.export') }}?" + params.toString(),
                "_blank"
            );
        });
        // END: Export
    });
</script>
@endsection