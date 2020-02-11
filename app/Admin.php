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

    static public function boot()
    {
        parent::boot();
        self::creating(function ($model)
        {
            $model->notifications = '[]';
        });
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool $save_credentials
     * @return bool
     * Returns true if admin credentials are valid
     */
    static function tryLogin(string $email, string $password, bool $save_credentials = false)
    {
        $credentials = self::query()->select(['password', 'id'])->where('email', 'like', $email)->get()->toArray()[0];
        if ($credentials !== null && Hash::check($password, $credentials['password']))
        {
            if ($save_credentials)
            {
                self::saveAdminCredentials($credentials['id'], $credentials['password']);
            }
            return true;
        }
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
        // x-credentials - admin ID and Password
        if (Cookie::has('x-credentials'))
        {
            $x_credentials = explode('|', Cookie::get('x-credentials'));
            return true;
            //return self::tryLogin($x_credentials[0], $x_credentials[1]);
        }
        return false;
    }

    /**
     * @param $id
     * @param $password
     * Saves admin credentials to cookies
     */
    static function saveAdminCredentials($id, $password)
    {
        $admin_info = self::query()->select(['name'])->where('id', '=', $id)->get()->toArray()[0];
        Cookie::queue('x-credentials', $id . '|' . $password . '|' .
            $admin_info['name'], 1440);
    }

    static function getAdminName()
    {
        if (!Cookie::has('x-credentials')) return redirect('admin');
        $decrypted_credentials = '';
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
        dd(explode('|', Cookie::get('x-credentials'))[0]);
        return explode('|', Cookie::get('x-credentials'))[0];
    }
}
