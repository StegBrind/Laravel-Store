<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $path = '/storage/' . $request->file('upload')->store('images/uploads', 'public');
        $func_number = $request->get('CKEditorFuncNum');
        echo "<script>window.parent.CKEDITOR.tools.callFunction($func_number, '$path', '');</script>";
    }
}
