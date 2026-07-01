<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Rating;
use App\Models\Ticket;
use App\Models\TicketFeedback;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CekTicketController extends Controller
{
    public function show()
    {
        $tickets = Ticket::with(['member' => function($query) {
            $query->select('id', 'username');
        }, 'rating'])->whereNull('deleted_at')->whereBetween('report_date', [Carbon::now()->firstOfMonth(), Carbon::now()->endOfMonth()])->get();

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
        ->when($request->ticket_title, function($query) use($request) {
            $query->where('ticket_title', 'like', "%{$request->ticket_title}%");
        })
        ->when($request->status, function($query) use($request) {
            $query->where('status_ticket', $request->status);
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

    public function log($ticketNo)
    {
        $ticket = Ticket::with('member')->where('ticket_no', $ticketNo)->select('id')->first();
        $logs = Log::with([
            'ticket.member' => function($query) {
                $query->select('id', 'username');
            },
            'ticket.users' => function($query) {
                $query->select('id', 'username');
            }
        ])
        ->where('ticket_id', $ticket->id)
        ->orderBy('log_date', 'DESC')
        ->get();

        $logs->makeHidden([
            'id',
            'ticket_id',
            'user_id',
            'created_at',
            'updated_at',
            'ticket_id'
        ]);

        return [
            'logs' => $logs,
            'ticket_no' => $ticketNo
        ];
    }

    public function respon(Request $request, $ticket_no)
    {
        DB::beginTransaction();
        try {
            // START: Get Request
            $datas = $request->all();
            // END: Get Request

            // START: Validation
            $rules = [
                'feedback' => 'required'
            ];

            $message = [
                'feedback.required' => 'Respon tidak boleh kosong.'
            ];

            $validator = Validator::make($datas, $rules, $message);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 422);
            }
            // END: Validation

            // START: Handle Feedback
            $status = 'on_progress';
            $now = Carbon::now();

            $ticket = Ticket::where('ticket_no', $ticket_no)->first();
            $ticket->update([
                'status_ticket' => $status,
                'updated_at' => $now
            ]);

            TicketFeedback::create([
                'ticket_id' => $ticket->id,
                'message' => $datas['feedback'],
                'role' => 'member',
                'user_id' => null
            ]);

            Log::create([
                'ticket_id' => $ticket->id,
                'user_id' => null,
                'status' => $status,
                'action_type' => 'feedback',
                'log_date' => $now,
            ]);
            // END: Handle Feedback

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Respon berhasil diberikan.'
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan.'
            ]);
        }
    }

    public function closed($ticket_no)
    {
        DB::beginTransaction();
        try {
            $now = Carbon::now();

            $ticket = Ticket::where('ticket_no', $ticket_no)->first();
            $ticket->update([
                'status_ticket' => 'completed',
                'closed_at' => $now,
                'updated_at' => $now
            ]);

            Log::create([
                'ticket_id' => $ticket->id,
                'user_id' => null,
                'status' => 'closed',
                'action_type' => 'closed',
                'log_date' => $now,
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Tiket berhasil ditutup.'
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan.'
            ]);
        }
    }

    public function rating(Request $request, $ticket_no)
    {
        DB::beginTransaction();
        try {
            // START: Get Request
            $datas = $request->all();
            // END: Get Request

            // START: Validation
            $rules = [
                'score' => 'required'
            ];

            $message = [
                'score.required' => 'Point tidak boleh kosong.'
            ];

            $validator = Validator::make($datas, $rules, $message);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 422);
            }
            // END: Validation

            // START: Handle Feedback
            $now = Carbon::now();

            $ticket = Ticket::where('ticket_no', $ticket_no)->first();
            Rating::create([
                'ticket_id' => $ticket->id,
                'score' => $datas['score'],
                'note' => $datas['note'] ?? null,
            ]);

            Log::create([
                'ticket_id' => $ticket->id,
                'user_id' => null,
                'status' => '',
                'action_type' => 'rating',
                'log_date' => $now,
                'description' => 'Pengguna memberikan rating ' . $datas['score'] . ' untuk penanganan tiket ini.'
            ]);
            // END: Handle Feedback

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Rating berhasil diberikan.'
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan.'
            ]);
        }
    }

    public function openTicket($ticket_no)
    {
        DB::beginTransaction();
        try {
            $ticket = Ticket::where('ticket_no', $ticket_no)->first();

            $status = 'on_progress';
            $now = Carbon::now();

            $ticket->update([
                'status_ticket' => $status,
                'closed_at' => null,
                'reopened_at' => $now
            ]);

            Log::create([
                'ticket_id' => $ticket->id,
                'user_id' => null,
                'status' => 'on_progress',
                'action_type' => 'open',
                'log_date' => $now,
                'description' => null
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Tiket berhasil dibuka kembali.'
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan.'
            ]);
        }
    }
}