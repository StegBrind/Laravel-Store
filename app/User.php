<?php

namespace App;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\VerifyMail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, CanResetPassword;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_surname', 'email', 'login', 'password', 'conversation_list'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyMail); // my notification
    }
    public function sendPasswordResetNotification($token)
    {
        $token = $this->tokenModify();
        $this->notify(new Notifications\ResetPasswordNotification($token));
    }

    private function tokenModify()
    {
        $new_token = Str::random(50);
        DB::table('password_resets')->where('email', '=', $this->getEmailForPasswordReset())
            ->update(['token' => Hash::make($new_token)]);
        return $new_token;
    }

    static public function getIdFromPath($path)
    {
        return substr($path, strrpos($path, '/') + 1);
    }

    static public function boot()
    {
        parent::boot();
        self::creating(function ($model)
        {
            $model->conversation_list = '[]';
        });
    }
}
