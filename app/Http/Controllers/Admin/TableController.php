<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    public function get(Request $request, $tableName)
    {
        $query = DB::table($tableName)->selectRaw($request->fields)
            ->limit($request->limit)->offset($request->offset);

        if ($request->has('sortBy'))
            $query->orderBy($request->sortBy, $request->sortDirection);


        $data['items'] = $query->get()->toArray();

        $data['count'] = DB::table($tableName)->count();

        return json_encode($data);
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
