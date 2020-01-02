<?php

namespace App;

use Cookie;
use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =
    [
        'name', 'email', 'password', 'notifications', 'last_read_notification_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden =
    [
        'password'
    ];

    static public function boot()
    {
        parent::boot();
        self::creating(function ($model)
        {
            $model->notifications = '[]';
        });
    }

    /**
     * @param $email
     * @param $password
     * @return bool
     * Returns true if admin credentials are valid
     */
    static function tryLogin($email, $password)
    {
        $hashed_password = self::query()->where('email', 'like', $email)->value('password');
        if ($hashed_password !== null && Hash::check($password, $hashed_password))
            return true;
        return false;
    }
    /**
     * @param $email
     * @param $password
     * @return bool
     * Returns true if admin cookies(x-credentials) are valid
     */
    static function tryLoginByCookies()
    {
        // x-credentials - admin Email and Password
        if (Cookie::has('x-credentials'))
        {
            $x_credentials = explode('|', Cookie::get('x-credentials'));
            return self::tryLogin($x_credentials[0], $x_credentials[1]);
        }
        return false;
    }

    /**
     * @param $email
     * @param $password
     * Saves admin credentials to cookies
     */
    static function saveAdminCredentials($email, $password)
    {
        $admin_info = self::query()->select(['name', 'id'])->where('email', 'like', $email)->get()->toArray()[0];
        Cookie::queue('x-credentials', $email . '|' . $password . '|' .
            $admin_info['name'] . '|' . $admin_info['id'], 1440);
    }

    static function getAdminName()
    {
        if (!Cookie::has('x-credentials')) return redirect('admin');
        $decrypted_credentials = "";
        try
        {
            $decrypted_credentials = explode('|', Crypt::decrypt(Cookie::get('x-credentials'), false));
        }
        catch (DecryptException $exception)
        {
            $decrypted_credentials = explode('|', Cookie::get('x-credentials'));
        }
        return $decrypted_credentials[2];
    }

    static function id(): int
    {
        return explode('|', Cookie::get('x-credentials'))[3];
    }
}
