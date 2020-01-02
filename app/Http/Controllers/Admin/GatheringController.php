<?php


namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GatheringController extends Controller
{
    public function getStatistics()
    {
        return DB::table('stats')->select('*')->get()->toArray();
    }

    public function exportUsers(Request $request)
    {
        if ($request->table == 'users')
        {
            return Excel::download(new UsersExport(
                [
                    'name_surname' => 'Имя Фамилия',
                    'email' => 'Почта',
                    'created_at' => 'Дата Регистрации'
                ]), 'users.xlsx');
        }
    }
}