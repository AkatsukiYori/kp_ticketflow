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
        $categories = Categories::query()
            ->select(['name', 'id'])->get();
        return view("admin.pages.documentation", compact('categories'));
    }

    public function datatable()
    {
        $documentation = Documentation::query()->orderBy("created_at", "DESC")->get();

        return DataTables::of($documentation)
            ->addIndexColumn()
            ->rawColumns([])
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
                "category.required" => "Category cannot be empty.",
                "title.required" => "Title cannot be empty."
            ];

            $validator = Validator::make($request->all(), $rules, $message);

            if($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
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

            $message = $id != '' ? 'updated' : "created";

            return response()->json([
                'status' => true,
                'message' => "Documentation successfully " . $message . "."
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => "Something went wrong."
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
                'message' => 'Documentation successfully deleted.'
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => "Something went wrong."
            ]);
        }
    }
}
