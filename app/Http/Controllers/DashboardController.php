<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function show()
    {
        $getTickets = Ticket::whereNull('deleted_at')->where('assign_to', Auth::id());
        $counter = [
            'all' => $getTickets->count(),
            'on_progress' => $getTickets->where('status_ticket', 'on_progress')->count(),
            'closed' => $getTickets->where('status_ticket', 'completed')->whereNotNull('closed_at')->count(),
            'reject' => $getTickets->where('status_ticket', 'reject')->whereNotNull('reject_at')->count()
        ];
        
        return view('admin.pages.dashboard', compact('counter'));
    }

    public function datatable()
    {
        $tickets = Ticket::whereNull('deleted_at')->where('assign_to', Auth::id())->get();
        return DataTables::of($tickets)
            ->addIndexColumn()
            ->addColumn('date', function($e) {
                return Carbon::parse($e->report_date)->locale('id')->translatedFormat('l, d F Y');
            })
            ->addColumn('status', function($e) {
                $pending = '<span class="badge bg-warning">Menunggu Proses</span>';
                $on_progress = '<span class="badge bg-secondary">Sedang Dikerjakan</span>';
                $feedback = '<span class="badge bg-primary">Umpan balik</span>';
                $reject = '<span class="badge bg-danger">Tolak</span>';

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
            ->rawColumns(['status', 'date'])
            ->make(true);
    }
}
