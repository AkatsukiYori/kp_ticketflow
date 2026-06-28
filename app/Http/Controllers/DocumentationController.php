<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Documentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\Facades\DataTables;

class DocumentationController extends Controller
{
    public function show()
    {
        $categories = Categories::query()->select(['name', 'id'])->get();
        return view("admin.pages.documentation", compact('categories'));
    }

    public function datatable(Request $request)
    {
        $documentation = Documentation::with('category')->orderBy("created_at", "DESC");

        if($request->filled('title')) {
            $documentation->where('title', 'like', '%' . $request->title . '%');
        }

        return DataTables::of($documentation)
            ->addIndexColumn()
            ->addColumn('kategori', function($e) {
                return $e->category->name;
            })
            ->addColumn('deskripsi', function($e) {
                return $e->description ?? '-';
            })
            ->addColumn('actions', function($e) {
                $hash = Hashids::encode($e->id);
                $url_edit = route('admin.pages.documentation.detail', $hash);
                $url_delete = route('admin.pages.documentation.delete', $hash);

                $edit = '<button
                        type="button"
                        id="btn-edit"
                        class="btn-edit"
                        data-url="' . $url_edit . '"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Perbarui Dokumentasi"
                    ><i class="fa-regular fa-pen-to-square" style="font-size: 1.3rem;"></i></button>';
                $delete = '<button
                        type="button"
                        id="btn-delete"
                        class="btn-delete"
                        data-url="' . $url_delete . '"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Hapus Dokumentasi"
                    ><i class="fa-regular fa-trash-can" style="font-size: 1.3rem;"></i></button>';

                return $edit . ' ' . $delete;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function createOrUpdate(Request $request)
    {
        try {
            // START: Get Request
            $id = $request->id;

            if($id) {
                $hash = Hashids::decode($id);
                $unHashedID = $hash[0] ?? null;
            }

            $category = $request->category;
            $title = $request->title;
            $description = $request->description;
            // END: Get Request

            // START: Validation
            $rules = [
                "category" => "required",
                "title" => "required"
            ];

            $message = [
                "category.required" => "Kategori tidak boleh kosong.",
                "title.required" => "Judul tidak boleh kosong."
            ];

            $validator = Validator::make($request->all(), $rules, $message);

            if($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 422);
            }
            // END: Validation

            // START: Documentation handle
            Documentation::updateOrCreate(
                ['id' => $unHashedID ?? null],
                [
                    'category_id' => $category,
                    'title' => $title,
                    'description' => $description
                ]
            );
            // END: Documentation handle

            $message = $id != '' ? 'diperbarui' : "dibuat";

            return response()->json([
                'status' => true,
                'message' => "Dokumentasi berhasil " . $message . "."
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => "Terjadi kesalahan."
            ]);
        }
    }

    public function detail($id)
    {
        $id = Hashids::decode($id);

        $unHashedID = $id[0] ?? null;
        $documentation = Documentation::findOrFail($unHashedID);
        $documentationID = Hashids::encode($documentation->id);
        $documentation->makeHidden([
            'id',
            'created_at',
            'updated_at'
        ]);

        return response()->json([
            'status' => true,
            'data' => $documentation,
            'hashed' => $documentationID
        ]);
    }

    public function delete($id)
    {
        try {
            $id = Hashids::decode($id);
            $unHashedID = $id[0] ?? null;

            Documentation::where('id', $unHashedID)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Dokumentasi berhasil dihapus.'
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => "Terjadi kesalahan."
            ]);
        }
    }
}
