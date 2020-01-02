<?php


namespace App\Http\Controllers\Admin;


use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function get()
    {
        return Admin::tryLoginByCookies() ? redirect('admin/dashboard') : view('admin.admin_login');
    }

    public function post(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);
        if ($validator->fails())
        {
            return redirect('admin')->withInput()->withErrors($validator->errors());
        }
        if (Admin::tryLogin($request->email, $request->password))
        {
            Admin::saveAdminCredentials($request->email, $request->password);
            return redirect('admin/dashboard');
        }
        return redirect('admin')->withErrors('Неверный E-Mail или пароль.')->withInput();
    }
}