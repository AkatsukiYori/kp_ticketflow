<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\DataTables;

class CategoriesController extends Controller
{
    public function show()
    {
        return view('admin.pages.category');
    }

    public function datatable()
    {
        $categories = Categories::query()->orderBy("created_at", "DESC")->get();

        return DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('actions', function ($row) {
                $hash = Hashids::encode($row->id);
                $url_edit = route('admin.pages.category.detail', $hash);
                $url_delete = route('admin.pages.category.delete', $hash);

                $edit = '<button type="button" id="btn-edit" class="btn-edit" data-url="' . $url_edit . '"><i class="fa-regular fa-pen-to-square" style="font-size: 1.3rem;"></i></button>';
                $delete = '<button type="button" id="btn-delete" class="btn-delete" data-url="' . $url_delete . '"><i class="fa-regular fa-trash-can" style="font-size: 1.3rem;"></i></button>';

                return $edit . ' ' . $delete;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function createOrUpdate(Request $request)
    {
        try {
            // START: Get request
            $id = $request->id;

            if ($id) {
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
                "name.required" => "Category name cannot be empty."
            ];

            $validator = Validator::make($request->all(), $rules, $message);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            // END: Validator

            // START: Category handle
            Categories::updateOrCreate(
                ['id' => $unHashedID ?? null],
                [
                    'name' => $name,
                ]
            );
            // END: Category handle

            $message = $id != "" ? "updated" : "created";

            return response()->json([
                "status" => true,
                "message" => "Category successfully " . $message . "."
            ]);
        } catch (Throwable $e) {
            return response()->json([
                "status" => false,
                "message" => "Something went wrong."
            ]);
        }
    }

    public function detail($id)
    {
        $id = Hashids::decode($id);

        $unHashedID = $id[0] ?? null;
        $categories = Categories::findOrFail($unHashedID);
        $categoriesId = Hashids::encode($categories->id);
        $categories->makeHidden([
            'id',
            'created_at',
            'updated_at',
            'deleted_at'
        ]);

        return response()->json([
            "status" => true,
            "data" => $categories,
            "hashed" => $categoriesId
        ]);
    }

    public function delete($id)
    {
        try {
            $id = Hashids::decode($id);
            $unHashedID = $id[0] ?? null;

            Categories::where('id', $unHashedID)->delete();

            return response()->json([
                "status" => true,
                "message" => "Category successfully deleted."
            ]);
        } catch (Throwable $e) {
            return response()->json([
                "status" => false,
                "message" => "Something went wrong."
            ]);
        }
    }
}
