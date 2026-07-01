<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Documentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
class DocumentationController extends Controller
{
    public function show()
    {
        $categories = Categories::query()->select(['name', 'id'])->get();
        return view("admin.pages.documentation", compact('categories'));
    }

    public function datatable(Request $request)
    {
        $documentation = Documentation::with('category', 'documentation_file')->orderBy("created_at", "DESC");

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
                        class="btn-edit border-0 bg-transparent"
                        data-url="' . $url_edit . '"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Perbarui Dokumentasi"
                    ><i class="fa-regular fa-pen-to-square" style="font-size: 1.3rem;"></i></button>';
                $delete = '<button
                        type="button"
                        id="btn-delete"
                        class="btn-delete border-0 bg-transparent"
                        data-url="' . $url_delete . '"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Hapus Dokumentasi"
                    ><i class="fa-regular fa-trash-can" style="font-size: 1.3rem;"></i></button>';

                return $edit . ' ' . $delete;
            })
            ->addColumn('attachment', function($e) {
                if(!$e->documentation_file) {
                    return '-';
                }

                $path = asset('storage/' . $e->documentation_file->file_path);
                return '<img src="'.$path.'" alt="Lampiran" class="img-fluid rounded">';
            })
            ->rawColumns(['actions', 'attachment'])
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
            $documentation = Documentation::updateOrCreate(
                ['id' => $unHashedID ?? null],
                [
                    'category_id' => $category,
                    'title' => $title,
                    'description' => $description
                ]
            );

            if($request->filled('attachment')) {
                $folder = $request->attachment;
                $disk = Storage::disk('public');

                $documentationPath = storage_path('app/public/documentations');
                if(!File::isDirectory($documentationPath)) {
                    File::makeDirectory($documentationPath, 0755, true);
                }
                $files = $disk->files("temp/$folder");

                if(!empty($files)) {
                    $oldFile = $documentation->documentation_file()->first();

                    if($oldFile) {
                        $disk->delete($oldFile->file_path);
                        $oldFile->delete();
                    }

                    $file = $files[0];
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    $filename = Str::uuid().'.'.$extension;
                    $destination = "documentation/$filename";
                    $absolutePath = storage_path("app/public/$destination");
                    $disk->move($file, $destination);
                    
                    $documentation->documentation_file()->create([
                        'document_id' => $documentation->id,
                        'filename' => $filename,
                        'file_path' => $destination,
                        'mime_type' => File::mimeType($absolutePath),
                        'size' => File::size($absolutePath),
                    ]);

                    $disk->deleteDirectory("temp/$folder");
                }
            }
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
        $documentation = Documentation::with('documentation_file')->findOrFail($unHashedID);
        $documentationID = Hashids::encode($documentation->id);
        $documentation->makeHidden([
            'id',
            'created_at',
            'updated_at'
        ]);

        return response()->json([
            'status' => true,
            'data' => $documentation,
            'file' => $documentation->documentation_file()->first(),
            'hashed' => $documentationID
        ]);
    }

    public function delete($id)
    {
        try {
            $id = Hashids::decode($id);
            $unHashedID = $id[0] ?? null;

            $documentation = Documentation::with('documentation_file')->where('id', $unHashedID)->first();
            if($documentation->documentation_file) {
                Storage::disk('public')->delete($documentation->documentation_file->file_path);
                $documentation->documentation_file()->delete();
            }
            $documentation->delete();

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
