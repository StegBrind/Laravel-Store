<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TreeController extends Controller
{
    public function get(Request $request, $tableName)
    {
        return json_encode(
            DB::table($tableName)->selectRaw($request->fields)->get()->toArray()
        );
    }

    public function set(Request $request, $tableName)
    {
        DB::table($tableName)->where($request->key, '=', $request->id)->update([$request->field => $request->value]);
    }

    public function delete(Request $request, $tableName)
    {
        DB::table($tableName)->delete($request->id);
    }
}
