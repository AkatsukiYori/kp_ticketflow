@forelse ($tickets as $ticket)
    <div class="d-flex justify-content-between align-items-center border px-4 py-2 w-100 rounded mt-3">
        <div class="card-body">
            <div class="card-body-header d-flex align-items-center">
                <p class="m-0">#{{ $ticket->ticket_no }}</p>
                <i class="fa-solid fa-circle px-3" style="font-size: 4px;"></i>
                <p class="m-0">{{ $ticket->report_date }}</p>
                <i class="fa-solid fa-circle px-3" style="font-size: 4px;"></i>
                <p class="m-0">{{ $ticket->member->username }}</p>
            </div>
            <div class="card-body-content">
                <h3 class="fs-4 fw-bold">{{ $ticket->ticket_title }}
                    <span
                        class="badge {{ $ticket->status_ticket === 'pending' ? 'bg-warning' : ($ticket->status_ticket === 'on_progress' ? 'bg-secondary' : ($ticket->status_ticket === 'completed' ? 'bg-success' : 'bg-danger')) }}"
                        style="font-size: .8rem;"
                    >{{ $ticket->status_ticket }}</span>
                </h3>
                <p>{{ $ticket->problem }}</p>
            </div>
            <div class="card-body-footer">
                @if($ticket->status_ticket === 'completed')
                    <button type="button" class="btn btn-danger btn-close-ticket"><i class="fa-solid fa-xmark"></i> Tutup Tiket</button>
                    <button type="button" class="btn btn-primary btn-respon" data-url="{{ route('cek_status.respon', $ticket->ticket_no) }}"><i class="fa-regular fa-comment-dots"></i> Respon</button>
                @elseif($ticket->status_ticket === 'closed')
                    <button type="button" class="btn btn-danger btn-open-ticket"><i class="fa-solid fa-lock-open"></i> Buka Tiket</button>
                    <button type="button" class="btn btn-warning btn-rating"><i class="fa-regular fa-star"></i> Rating</button>
                @endif
                <button type="button" class="btn btn-secondary btn-log" data-url="{{ route('cek_status.log', $ticket->ticket_no) }}"><i class="fa-solid fa-bars"></i> Log</button>
            </div>
        </div>
        <div class="card-detail">
            <button
                type="button"
                class="btn-detail bg-transparent border-0"
                data-toggle="tooltip"
                data-placement="bottom"
                title="Detail Tiket"
                data-ticket="{{ $ticket->ticket_no }}"
            ><i class="fa-regular fa-circle-right fs-4"></i></button>
        </div>
    </div>
@empty
    <center class="mt-3 fs-4 fw-bold">
        Tidak ada tiket.
    </center>
@endforelse