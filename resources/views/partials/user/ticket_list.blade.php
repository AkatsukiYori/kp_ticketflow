@forelse ($tickets as $ticket)
    <div class="d-flex justify-content-between align-items-center border p-4 w-100 rounded mt-3">
        <div class="card-body">
            <div class="card-body-header d-flex align-items-center">
                <p>#{{ $ticket->ticket_no }}</p>
                <i class="fa-solid fa-circle px-3" style="font-size: 2px;"></i>
                <p>{{ $ticket->report_date }}</p>
                <i class="fa-solid fa-circle px-3" style="font-size: 2px;"></i>
                <p>{{ $ticket->member->username }}</p>
            </div>
            <div class="card-body-content">
                <h3 class="fs-4 fw-bold">{{ $ticket->ticket_title }}</h3>
                <p>{{ $ticket->problem }}</p>
            </div>
            <div class="card-body-footer">
                <p>Tombol</p>
            </div>
        </div>
        <div class="card-detail">
            <button
                type="button"
                class="btn-detail"
                data-toggle="tooltip"
                data-placement="bottom"
                title="Detail Tiket"
                data-ticket="{{ $ticket->ticket_no }}"
            ><i class="fa-solid fa-circle-arrow-right"></i></button>
        </div>
    </div>
@empty
    <center class="mt-3 fs-3 fw-bold">
        Tidak ada tiket.
    </center>
@endforelse