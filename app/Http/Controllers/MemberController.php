<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;

class MemberController extends Controller
{
    public function show() {
        return view('admin.pages.user');
    }

    public function datatable(Request $request) {
        $users = Member::query()->orderBy("created_at", "DESC");

        if($request->filled('username')) {
            $users->where('username', 'like', '%' . $request->username . '%');
        }
        
        return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('actions', function($row) {
                $hash = Hashids::encode($row->id);
                $url_edit = route('admin.pages.member.detail', $hash);
                $url_delete = route('admin.pages.member.delete', $hash);

                $edit = '<button
                        type="button"
                        id="btn-edit"
                        class="btn-edit border-0 bg-transparent"
                        data-url="'.$url_edit.'"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Perbarui Pengguna"
                    ><i class="fa-regular fa-pen-to-square" style="font-size: 1.3rem;"></i></button>';
                $delete = '<button
                        type="button"
                        id="btn-delete"
                        class="btn-delete border-0 bg-transparent"
                        data-url="'.$url_delete.'"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Hapus Pengguna"
                    ><i class="fa-regular fa-trash-can" style="font-size: 1.3rem;"></i></button>';

                return $edit . ' ' . $delete;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function createOrUpdate(Request $request) {
        try {
            // START: Get request
            $id = $request->id;

            if($id) {
                $hash = Hashids::decode($id);
                $unHashedID = $hash[0] ?? null;
            }

            $name = $request->name;
            // END: Get request

            // START: Validator
            $rules = [
                "name" => "required"
            ];

            $message = [
                "name.required" => "Nama pengguna tidak boleh kosong."
            ];
            
            $validator = Validator::make($request->all(), $rules, $message);

            if($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 422);
            }
            // END: Validator

            // START: User handle
            Member::updateOrCreate(
                ['id' => $unHashedID ?? null],
                ['username' => $name, 'is_active' => true]
            );
            // END: User handle

            $message = $id != "" ? "diperbarui" : "dibuat";

            return response()->json([
                "status" => true,
                "message" => "Pengguna berhasil ".$message."."
            ]);
        } catch (Throwable $e) {
            return response()->json([
                "status" => false,
                "message" => "Terjadi kesalahan."
            ]);
        }
    }

    public function detail($id) {
        $id = Hashids::decode($id);

        $unHashedID = $id[0] ?? null;
        $user = Member::findOrFail($unHashedID);
        $userId = Hashids::encode($user->id);
        $user->makeHidden([
            'id',
            'is_active',
            'created_at',
            'updated_at',
            'deleted_at'
        ]);

        return response()->json([
            "status" => true,
            "data" => $user,
            "hashed" => $userId
        ]);
    }

    public function delete($id) {
        try {
            $id = Hashids::decode($id);
            $unHashedID = $id[0] ?? null;

            Member::where('id', $unHashedID)->delete();

            return response()->json([
                "status" => true,
                "message" => "Pengguna berhasil dihapus."
            ]);
        } catch (Throwable $e) {
            return response()->json([
                "status" => false,
                "message" => "Terjadi kesalahan."
            ]);
        }
    }
}
