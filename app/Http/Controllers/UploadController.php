<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadTemp(Request $request)
    {
        $folder = uniqid();
        $request->file('attachment')->storeAs(
            'temp/'.$folder,
            $request->file('attachment')->getClientOriginalName(),
            'public'
        );

        return response($folder);
    }

    public function uploadRevert(Request $request)
    {
        Storage::disk('public')->deleteDirectory('temp/'.$request->getContent());

        return response()->noContent();
    }
}
