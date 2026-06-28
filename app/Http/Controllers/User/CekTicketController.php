<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CekTicketController extends Controller
{
    public function show()
    {
        $tickets = Ticket::with(['member' => function($query) {
            $query->select('id', 'username');
        }])->whereNull('deleted_at')->whereBetween('report_date', [Carbon::now()->firstOfMonth(), Carbon::now()->endOfMonth()])->get();

        $tickets->makeHidden([
            'id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
        return view('user.pages.cekticket', compact('tickets'));
    }

    public function detail($ticketNo)
    {
        $ticket = Ticket::with('member', 'users', 'category', 'department')->where('ticket_no', $ticketNo)->firstOrFail();
        $ticket->category_name = $ticket->category->name;
        $ticket->department_name = $ticket->department->name;
        $ticket->member_name = $ticket->member->username;
        $ticket->users_name = $ticket->users->name ?? '-';

        $ticket->makeHidden([
            'id',
            'assign_to',
            'category_id',
            'department_id',
            'member_id',
            'created_at',
            'updated_at',
            'deleted_at',
            'category',
            'member',
            'users',
            'department'
        ]);

        return $ticket;
    }

    public function filter(Request $request)
    {
        $tickets = Ticket::with(['member' => function($query) {
            $query->select('id', 'username');
        }])
        ->whereNull('deleted_at')
        ->when($request->ticket_no, function($query) use($request) {
            $query->where('ticket_no', 'like', "%{$request->ticket_no}%");
        })
        ->when($request->member_name, function($query) use($request) {
            $query->whereHas('member', function($q) use($request) {
                $q->where('username', 'like', "%{$request->member_name}%");
            });
        })
        ->when($request->first_date && $request->end_date, function($query) use($request) {
            $query->whereBetween('report_date', [$request->first_date, $request->end_date]);
        })
        ->get();

        $tickets->makeHidden([
            'id',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
        return view('partials.user.ticket_list', compact('tickets'));
    }
}
