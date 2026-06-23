<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Log;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
    public function show()
    {
        $users = User::select('id', 'username')->get();
        return view('admin.pages.ticket', compact('users'));
    }

    public function datatable()
    {
        $tickets = Ticket::query()->orderBy('created_at', 'DESC')->get();

        return DataTables::of($tickets)
            ->addIndexColumn()
            ->editColumn('tanggal', function($e) {
                return Carbon::parse($e->report_date)->locale('id')->translatedFormat('l, d F Y');
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
            ->editColumn('pengguna', function($e) {
                return $e->member->username;
            })
            ->editColumn('pic', function($e) {
                return $e->users->username ?? '-';
            })
            ->addColumn('actions', function($e) {
                $ticket_no = $e->ticket_no;
                $status = $e->status_ticket;

                $url_assign = route('admin.pages.ticket.assign', $ticket_no);
                $url_delete = route('admin.pages.ticket.delete', $ticket_no);
                $url_reject = route('admin.pages.ticket.reject', $ticket_no);

                $assign = '<button class="dropdown-item py-2 px-3 btn-assign" data-url="'. $url_assign .'" data-ticket="'. $ticket_no .'"><i class="fa-solid fa-user-check fs-6"></i> Assign</button><hr>';
                $re_assign = '<button class="dropdown-item py-2 px-3 btn-re-assign" data-url="'. $url_assign .'" data-ticket="'. $ticket_no .'"><i class="fa-solid fa-user-check fs-6"></i> Re Assign</button><hr>';
                $feedback = '<button class="dropdown-item py-2 px-3"><i class="fa-regular fa-circle-check fs-6"></i> Feedback</button><hr>';
                $reject = '<button class="dropdown-item py-2 px-3 btn-reject" data-url="'. $url_reject .'" data-ticket="'. $ticket_no .'"><i class="fa-regular fa-circle-xmark fs-6"></i> Reject</button><hr>';
                $remove = '<button class="dropdown-item py-2 px-3 btn-remove text-danger" data-url="'. $url_delete .'"><i class="fa-solid fa-trash fs-6"></i> Remove</button>';

                if($status === 'reject') {
                    $listButton = $remove;
                } else if($status === 'completed') {
                    $listButton = $re_assign . ' ' . $feedback . ' ' . $reject . ' ' . $remove;
                } else if($status === 'on_progress') {
                    $listButton = $re_assign . ' ' . $feedback . ' ' . $reject . ' ' . $remove;
                } else {
                    $listButton = $assign . ' ' . $reject . ' ' . $remove;
                }

                $btn_group = '
                    <div class="dropdown">
                        <button class="dropdown-btn js-dropdown-btn">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>

                        <div class="dropdown-menu">
                            '.$listButton.'
                        </div>
                    </div>
                ';
                $detail = '<button type="button" class="btn-detail" data-ticket="'.$ticket_no.'"><i class="fa-solid fa-info"></i></button>';

                return $detail . " " . $btn_group;
            })
            ->rawColumns(['tanggal', 'status', 'actions'])
            ->make(true);
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

    public function delete($ticket_no)
    {
        try {
            Ticket::where('ticket_no', $ticket_no)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Tiket berhasil dihapus.'
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.'
            ]);
        }
    }

    public function assign(Request $request, $ticket_no)
    {
        DB::beginTransaction();
        try {
            // START: Get Request
            $datas = $request->all();
            // END: Get Request

            // START: Validation
            $rules = [
                'pic' => 'required',
                'priority' => 'required',
                'estimate' => 'required'
            ];

            $message = [
                'pic.required' => 'PIC tidak boleh kosong.',
                'priority.required' => 'Prioritas tidak boleh kosong.',
                'estimate.required' => 'Estimasi tidak boleh kosong.',
            ];

            $validator = Validator::make($datas, $rules, $message);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 422);
            }
            // END: Validation

            // START: Handle Assign
            $status = 'on_progress';
            $now = Carbon::now();

            $ticket = Ticket::where('ticket_no', $ticket_no)->first();
            $ticket->update([
                'assign_to' => $datas['pic'],
                'status_ticket' => $status,
                'estimate' => $datas['estimate'],
                'priority' => $datas['priority'],
                'updated_at' => $now
            ]);

            Log::create([
                'ticket_id' => $ticket->id,
                'user_id' => $datas['pic'],
                'status' => $status,
                'action_type' => $datas['flag'],
                'log_date' => $now,
                'description' => null
            ]);
            // END: Handle Assign

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Tiket berhasil diupdate'
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.'
            ]);
        }
    }

    public function reject(Request $request, $ticket_no)
    {
        DB::beginTransaction();
        try {
            // START: Get Request
            $datas = $request->all();
            $status = 'reject';
            $now = Carbon::now();
            // END: Get Request

            // START: Validator
            $rules = [
                'reason' => 'required'
            ];

            $message = [
                'reason.required' => 'Alasan tolak tidak boleh kosong.'
            ];

            $validator = Validator::make($datas, $rules, $message);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 422);
            }
            // END: Validator

            // START: Handle Reject
            $ticket = Ticket::where('ticket_no', $ticket_no)->first();
            $ticket->update([
                'status_ticket' => $status,
                'status_reason' => $datas['reason'],
                'reject_at' => $now
            ]);

            Log::create([
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->assign_id,
                'status' => $status,
                'action_type' => 'reject',
                'log_date' => $now,
                'description' => $datas['reason'],
                'updated_at' => $now
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Tiket berhasil ditolak.'
            ]);
            // END: Handle Reject
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => true,
                'message' => 'Something went wrong.'
            ]);
        }
    }
}
