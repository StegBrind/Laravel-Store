<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Admin;
use AdminSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard()
    {
        return AdminSection::view(view('admin.admin_chart'));
    }

    public function getGA()
    {
        return AdminSection::view(view('admin.admin_ga'));
    }

    public function quitAdmin()
    {
        \Cookie::queue(\Cookie::forget('x-credentials'));
        return redirect('admin');
    }

    public function createNewAdmin(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
                'name' => 'required'
            ]);
        if ($validator->fails())
            return redirect('admin/admins/create')->withInput()->withErrors($validator->errors());
        Admin::query()->create(['name' => $request->name, 'email' => $request->email, 'password' => \Hash::make($request->password)]);
        return redirect('admin/admins');
    }
}