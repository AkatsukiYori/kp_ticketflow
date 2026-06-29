<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use App\Models\Log;
use App\Models\Ticket;
use App\Models\TicketFeedback;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class IkbController extends Controller
{
    public function show()
    {
        $users = User::select('id', 'username')->get();
        return view('admin.pages.ikb', compact('users'));
    }

    public function datatable(Request $request)
    {
        $tickets = Ticket::query()
            ->whereHas('category', function($query) {
                $query->whereLike('name', '%IKB%');
            })->orderBy('created_at', 'DESC');

        if($request->filled('ticket_title')) {
            $tickets->whereLike('ticket_title', '%' . $request->ticket_title . '%');
        }

        if($request->filled('status')) {
            $tickets->where('status_ticket', $request->status);
        }

        if($request->filled('status_point')) {
            $tickets->where('ikb_status_point', $request->status_point);
        }

        return DataTables::of($tickets)
            ->addIndexColumn()
            ->addColumn('tanggal', function($e) {
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
            ->addColumn('status_point', function($e) {
                $bugs = '<span class="badge bg-primary">Bugs</span>';
                $additional = '<span class="badge bg-success">Additional</span>';
                $not_set = '<span class="badge bg-secondary">Belum ditentukan</span>';

                if($e->ikb_status_point == 'bugs') {
                    return $bugs;
                } else if ($e->ikb_status_point == 'additional') {
                    return $additional;
                } else {
                    return $not_set;
                }
            })
            ->addColumn('actions', function($e) {
                $ticket_no = $e->ticket_no;
                $status = $e->status_ticket;

                $url_assign = route('admin.pages.ikb.assign', $ticket_no);
                $url_delete = route('admin.pages.ikb.delete', $ticket_no);
                $url_reject = route('admin.pages.ikb.reject', $ticket_no);
                $url_feedback = route('admin.pages.ikb.feedback', $ticket_no);
                $url_update = route('admin.pages.ikb.update', $ticket_no);

                $assign = '<button class="dropdown-item px-3 btn-assign" data-url="'. $url_assign .'" data-ticket="'. $ticket_no .'" data-toggle="tooltip" data-placement="bottom" title="Ambil Tiket"><i class="fa-solid fa-user-check fs-6"></i> Ambil Tiket</button><hr class="m-2">';
                $re_assign = '<button class="dropdown-item px-3 btn-re-assign" data-url="'. $url_assign .'" data-ticket="'. $ticket_no .'" data-toggle="tooltip" data-placement="bottom" title="Pindah Penugasan Tiket"><i class="fa-solid fa-user-check fs-6"></i> Pindah Penugasan</button><hr class="m-2">';
                $feedback = '<button class="dropdown-item px-3 btn-feedback" data-url="'. $url_feedback .'" data-ticket="'. $ticket_no .'" data-toggle="tooltip" data-placement="bottom" title="Umpan Balik"><i class="fa-regular fa-circle-check fs-6"></i> Feedback</button><hr class="m-2">';
                $reject = '<button class="dropdown-item px-3 btn-reject" data-url="'. $url_reject .'" data-ticket="'. $ticket_no .'" data-toggle="tooltip" data-placement="bottom" title="Tolak"><i class="fa-regular fa-circle-xmark fs-6"></i> Tolak</button><hr class="m-2">';
                $update = '<button class="dropdown-item px-3 btn-update" data-url="'. $url_update .'" data-ticket="'. $ticket_no .'" data-toggle="tooltip" data-placement="bottom" title="Ubah Tiket"><i class="fa-solid fa-pencil fs-6"></i> Edit</button><hr class="m-2">';
                $remove = '<button class="dropdown-item px-3 btn-remove text-danger" data-url="'. $url_delete .'" data-toggle="tooltip" data-placement="bottom" title="Hapus"><i class="fa-solid fa-trash fs-6"></i> Hapus</button>';

                if($status === 'reject') {
                    $listButton = $remove;
                } else if($status === 'completed') {
                    $listButton = $re_assign . ' ' . $feedback . ' ' . $reject . ' ' . $update . ' ' . $remove;
                } else if($status === 'on_progress') {
                    $listButton = $re_assign . ' ' . $feedback . ' ' . $reject . ' ' . $update . ' ' . $remove;
                } else {
                    $listButton = $assign . ' ' . $reject . ' ' . $remove;
                }

                $btn_group = '
                    <div class="dropdown">
                        <button class="dropdown-btn js-dropdown-btn bg-transparent border-0" data-toggle="tooltip" data-placement="bottom" title="Lainnya">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>

                        <div class="dropdown-menu">
                            '.$listButton.'
                        </div>
                    </div>
                ';
                $detail = '<button type="button" class="btn-detail bg-transparent border-0" data-ticket="'.$ticket_no.'" data-toggle="tooltip" data-placement="bottom" title="Detail Tiket"><i class="fa-solid fa-info"></i></button>';

                return $detail . " " . $btn_group;
            })
            ->rawColumns(['tanggal', 'status', 'actions', 'status_point'])
            ->make(true);
    }

    public function detail($ticketNo)
    {
        $ticket = Ticket::with('member', 'users', 'category', 'department')->whereHas('category', function($query) {
            $query->whereLike('name', '%IKB');
        })->where('ticket_no', $ticketNo)->firstOrFail();
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
        DB::beginTransaction();
        try {
            $ticket = Ticket::where('ticket_no', $ticket_no)->firstOrFail();

            Log::create([
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->assign_to,
                'status' => 'remove',
                'action_type' => 'delete',
                'log_date' => Carbon::now(),
                'description' => null
            ]);

            $ticket->delete();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Tiket berhasil dihapus.'
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
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
                'estimate' => 'required',
                'status_point' => 'required',

            ];

            $message = [
                'pic.required' => 'PIC tidak boleh kosong.',
                'priority.required' => 'Prioritas tidak boleh kosong.',
                'estimate.required' => 'Estimasi tidak boleh kosong.',
                'status_point.required' => 'Status poin tidak boleh kosong.',
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
                'ikb_status_point' => $datas['status_point'],
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

    public function feedback(Request $request, $ticket_no)
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
                'feedback.required' => 'Feedback tidak boleh kosong.'
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
            $status = 'completed';
            $now = Carbon::now();

            $ticket = Ticket::where('ticket_no', $ticket_no)->first();
            $ticket->update([
                'status_ticket' => $status,
                'updated_at' => $now
            ]);

            if($datas['add_documentation'] === "1") {
                Documentation::create([
                    'category_id' => $ticket->category_id,
                    'title' => $ticket->ticket_title,
                    'description' => $datas['feedback']
                ]);
            }

            TicketFeedback::create([
                'ticket_id' => $ticket->id,
                'message' => $datas['feedback'],
                'role' => 'admin',
                'user_id' => $ticket->assign_to
            ]);

            Log::create([
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->assign_to,
                'status' => $status,
                'action_type' => 'feedback',
                'log_date' => $now,
                'description' => null
            ]);
            // END: Handle Feedback

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Umpan balik berhasil diberikan.'
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.'
            ]);
        }
    }

    public function update(Request $request, $ticket_no)
    {
        try {
            // START: Get Request
            $datas = $request->all();
            // END: Get Request

            // START: Validation
            $rules = [
                'status_point' => 'required'
            ];

            $message = [
                'status_point' => 'required'
            ];

            $validator = Validator::make($datas, $rules, $message);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 422);
            }
            // END: Validation

            // START: Handle Update
            $ticket = Ticket::where('ticket_no', $ticket_no)->first();
            $ticket->update([
                'ikb_status_point' => $datas['status_point']
            ]);
            // END: Handle Update

            return response()->json([
                'status' => true,
                'message' => 'Tiket berhasil diperbarui.'
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan.'
            ]);
        }
    }
}
