<?php

namespace App\Http\Controllers;

use App\Exports\TicketExport;
use App\Models\Categories;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class ReportController extends Controller
{
    public function show()
    {
        $years = Ticket::selectRaw('YEAR(created_at) as year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $categories = Categories::select('id', 'name')->get();
        return view('admin.pages.report', compact('years', 'categories'));
    }
    
    public function filter(Request $request)
    {
        // dd($request->all());
        $getTickets = Ticket::whereNull('deleted_at');

        // START: Get Month & Year Data
        if($request->category) {
            $getTickets->where('category_id', $request->category);
        }

        if($request->month) {
            $getTickets->whereMonth('report_date', $request->month);
        }

        if($request->year) {
            $getTickets->whereYear('report_date', $request->year);
        }
        // END: Get Month & Year Data

        // START: Card Ticket Counter
        $counter = [
            'all' => (clone $getTickets)->count(),
            'pending' => (clone $getTickets)->where('status_ticket', 'pending')->count(),
            'on_progress' => (clone $getTickets)->where('status_ticket', 'on_progress')->count(),
            'closed' => (clone $getTickets)->where('status_ticket', 'completed')->whereNotNull('closed_at')->count(),
            'reject' => (clone $getTickets)->where('status_ticket', 'reject')->whereNotNull('reject_at')->count()
        ];
        // END: Card Ticket Counter

        // START: Count Ticket Per Periode
        $months = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'July',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        $days = Carbon::create(
            $request->year,
            $request->month,
            1
        )->daysInMonth();
        $getTicketData = [];

        if ($request->month) {
            $ticketCounter = Ticket::selectRaw('DAY(report_date) as day, COUNT(*) as total')
                ->whereYear('report_date', $request->year)
                ->whereMonth('report_date', $request->month)
                ->when($request->filled('category'), function($q) use($request) {
                    $q->where('category_id', $request->category);
                })
                ->groupBy('day')
                ->orderBy('day')
                ->pluck('total', 'day');

            for($day = 1; $day <= $days; $day++) {
                $getTicketData[] = [
                    'label' => $day,
                    'count' => $ticketCounter[$day] ?? 0
                ];
            }
        } else {
            $ticketCounter = Ticket::selectRaw('MONTH(report_date) as month, COUNT(*) as total')
                ->whereYear('report_date', $request->year)
                ->when($request->filled('category'), function($q) use($request) {
                    $q->where('category_id', $request->category);
                })
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');

            foreach($months as $index => $month) {
                $monthNumber = $index + 1;

                $getTicketData[] = [
                    'label' => $month,
                    'count' => $ticketCounter[$monthNumber] ?? 0
                ];
            }
        }
        // END: Count Ticket Per Periode
        
        // START: Bar Chart Category Counter
        $categories = Categories::withCount([
            'ticket' => function ($query) use($request) {
                $query->whereNull('deleted_at');

                if($request->month) {
                    $query->whereMonth('report_date', $request->month);
                }
                
                if($request->year) {
                    $query->whereYear('report_date', $request->year);
                }

                if($request->category) {
                    $query->where('category_id', $request->category);
                }
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

        // START: Rating Counter
        $ratings = [5, 4, 3, 2, 1];
        $getRatingData = [];
        $totalRating = (clone $getTickets)->whereHas('rating')->count();
        foreach($ratings as $rating) {
            $count = (clone $getTickets)->whereHas('rating', function($query) use($rating) {
                $query->where('score', $rating);
            })->count();

            $getRatingData[] = [
                'label' => "Score {$rating}",
                'count' => $count,
                'percentage' => $totalRating ? round(($count / $totalRating) * 100) : 0
            ];
        }
        // END: Rating Counter

        return response()->json([
            'counter' => $counter,
            'categoryCounter' => $getCategoryData,
            'priorityCounter' => $getPriorityData,
            'ratingCounter' => $getRatingData,
            'ticketCounter' => $getTicketData
        ]);
    }

    public function export(Request $request)
    {
        $unique = uniqid();
        return Excel::download(
            new TicketExport($request),
            'Laporan-tiket-' . $unique . '.xlsx'
        );
    }
}
