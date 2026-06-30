<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function show()
    {
        return view('admin.pages.dashboard');
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

    public function filter()
    {
        $getTickets = Ticket::whereNull('deleted_at')->where('assign_to', Auth::id());

        // START: Card Ticket Counter
        $counter = [
            'all' => (clone $getTickets)->count(),
            'on_progress' => (clone $getTickets)->where('status_ticket', 'on_progress')->count(),
            'closed' => (clone $getTickets)->where('status_ticket', 'completed')->whereNotNull('closed_at')->count(),
            'reject' => (clone $getTickets)->where('status_ticket', 'reject')->whereNotNull('reject_at')->count()
        ];
        // END: Card Ticket Counter
        
        // START: Bar Chart Category Counter
        $categories = Categories::withCount([
            'ticket' => function ($query) {
                $query->whereNull('deleted_at')->where('assign_to', Auth::id());
            }
        ])->get();

        $getCategoryData = [];
        foreach($categories as $category) {
            $getCategoryData[] = [
                'count' => $category->ticket_count,
                'label' => $category->name
            ];
        }
        // END: Bar Chart Category Counter

        // START: Pie Chart Priority Counter
        $priorities = ['low', 'mid', 'high'];
        $getPriorityData = [];
        foreach($priorities as $priority) {
            $getPriorityData[] = [
                'count' => (clone $getTickets)->where('priority', $priority)->count(),
                'label' => $priority
            ];
        }
        // END: Pie Chart Priority Counter

        

        return response()->json([
            'counter' => $counter,
            'categoryCounter' => $getCategoryData,
            'priorityCounter' => $getPriorityData
        ]);
    }
}
