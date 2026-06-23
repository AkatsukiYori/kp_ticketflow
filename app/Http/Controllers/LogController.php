<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Ticket;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\Facades\DataTables;

class LogController extends Controller
{
    public function show()
    {
        return view("admin.pages.logs");
    }

    public function datatable()
    {
        $logs = Ticket::with('log', 'users', 'member')->orderBy('report_date', 'DESC')->get();
        return DataTables::of($logs)
            ->addIndexColumn()
            ->addColumn('pengguna', function($e) {
                return $e->member->username;
            })
            ->addColumn('tanggal', function($e) {
                return Carbon::parse($e->report_date)->locale('id')->translatedFormat('l, Y F d');
            })
            ->addColumn('actions', function($e) {
                $url = route('admin.pages.logs.detail', $e->ticket_no);
                return '<button type="button" id="btn-detail" data-url="'. $url .'"><i class="fa-solid fa-ellipsis-vertical"></i></button>';
            })
            ->addColumn('no_ticket', function($e) {
                return $e->ticket_no;
            })
            ->addColumn('judul_ticket', function($e) {
                return $e->ticket_title;
            })
            ->editColumn('status', function($e) {
                $pending = '<span class="badge bg-warning">Pending</span>';
                $on_progress = '<span class="badge bg-secondary">On Progress</span>';
                $feedback = '<span class="badge bg-primary">Feedback</span>';
                $reject = '<span class="badge bg-danger">Reject</span>';

                if($e->status_ticket == 'reject') {
                    return $reject;
                } else if($e->status_ticket == 'completed') {
                    return $feedback;
                } else if($e->status_ticket == 'on_progress') {
                    return $on_progress;
                } else {
                    return $pending;
                }
            })
            ->rawColumns(['actions', 'status'])
            ->make(true);
    }

    public function detail($ticket_no)
    {
        $ticket = Ticket::with('member')->where('ticket_no', $ticket_no)->select('id')->first();
        $logs = Log::with('ticket.member', 'ticket.users')->where('ticket_id', $ticket->id)->get();

        $logs->makeHidden([
            'id',
            'ticket_id',
            'user_id',
            'created_at',
            'updated_at',
            'ticket_id'
        ]);

        return $logs;
    }
}
