<?php

namespace App\Http\Controllers;

use App\Jobs\AdminMailingProcess;
use App\Notifications\AdminSendNotification;
use App\User;
use App\Admin;
use App\AdminNotificationHistory;
use AdminSection;
use AdminTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
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

    public function dashboard()
    {
        return view('admin.custom_layout.inner', [
            'title' => 'Admin',
            'content' => view('admin.admin_chart')->render(),
            'template' => AdminSection::view(AdminTemplate::getViewPath('_layout.base'))->getData()['template']
        ]);
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

    public function sendEmailNotifications(Request $request)
    {
        if (trim($request->subject) == '') $request->subject = 'Без темы';
        $users = $request->emails_confirmed == 'true' ? User::query()->whereNotNull('email_verified_at')->get() : User::all();

        AdminNotificationHistory::query()->insert(['subject' => $request->subject, 'content_message' => $request->message_text]);

        AdminMailingProcess::dispatch(
            $users,
            new AdminSendNotification(['subject' => $request->subject, 'text' => $request->message_text]),
            AdminNotificationHistory::query()->latest('id')->first()
        );
    }

    public function getStatistics()
    {
        return DB::table('stats')->select('*')->get('*')->toArray();
    }
}