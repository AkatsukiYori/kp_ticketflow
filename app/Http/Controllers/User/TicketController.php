<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Department;
use App\Models\Log;
use App\Models\Member;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class TicketController extends Controller
{
    public function show()
    {
        $members = Member::where('is_active', 1)->select(['id', 'username'])->orderBy('username', 'ASC')->get();
        $departments = Department::select(['id', 'name'])->orderBy('name', 'ASC')->get();
        $categories = Categories::select(['id', 'name'])->orderBy('name', 'ASC')->get();

        return view('user.pages.ticket', compact('members', 'departments', 'categories'));
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            // START: Get Request
            $datas = $request->all();
            // END: Get Request

            // START: Check kategori
            $get_category = Categories::findOrFail($datas['kategori']);
            $is_ikb = $get_category->name == "ikb" || $get_category->name == "IKB";
            // END: Check kategori

            // START: Validation
            $rules = [
                'pengguna' => 'required',
                'no_wa' => 'required',
                'departemen' => 'required',
                'lokasi' => 'required',
                'judul_ticket' => 'required|max:20',
                'kategori' => 'required',
                'kendala' => 'required|max:1000',
                'modul' => $is_ikb ? 'required' : '',
                'sub_modul' => $is_ikb ? 'required' : '',
            ];

            $message = [
                'pengguna.required' => 'Pengguna tidak boleh kosong.',
                'no_wa.required' => 'No WA tidak boleh kosong.',
                'departemen.required' => 'Departemen tidak boleh kosong.',
                'lokasi.required' => 'Lokasi tidak boleh kosong.',
                'judul_ticket.required' => 'Judul ticket tidak boleh kosong.',
                'kategori.required' => 'Kategori tidak boleh kosong.',
                'kendala.required' => 'Kendala tidak boleh kosong.'
            ];

            $validator = Validator::make($datas, $rules, $message);

            if($validator->fails()) {
                return response()->json([
                    'status' =>  false,
                    'errors' => $validator->errors()
                ], 422);
            }
            // END: Validation

            // START: Generate no ticket
            $today = Carbon::today();

            $lastTicket = Ticket::whereDate('report_date', $today)->orderByDesc('id')->first();
            if($lastTicket) {
                $lastNumber = (int) substr($lastTicket->ticket_no, -3);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $ticketNo = sprintf(
                'TKT-%s-%03d',
                Carbon::now()->format("Ymd"),
                $nextNumber
            );
            // END: Generate no ticket

            // START: Ticket handle
            $newTicket = Ticket::create([
                'category_id' => $datas['kategori'],
                'department_id' => $datas['departemen'],
                'member_id' => $datas['pengguna'],
                'ticket_no' => $ticketNo,
                'ticket_title' => $datas['judul_ticket'],
                'problem' => $datas['kendala'],
                'no_wa' => $datas['no_wa'],
                'report_date' => Carbon::now(),
                'location' => $datas['lokasi'],
                'note' => $datas['note'],
                'status_ticket' => 'pending',
                'modul' => $datas['modul'],
                'sub_modul' => $datas['sub_modul']
            ]);
            // END: Ticket handle

            // START: Logs handle
            Log::create([
                'ticket_id' => $newTicket->id,
                'user_id' => null,
                'status' => $newTicket->status_ticket,
                'action_type' => 'create',
                'log_date' => Carbon::now(),
                'description' => null,
                'auto_closed' => false,
                'closed_by' => null
            ]);
            // END: Logs handle

            DB::commit();
            return response()->json([
                'status' => true,
                'ticket_no' => $ticketNo
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => "Something went wrong."
            ]);
        }
    }
}