<?php

namespace App;

use App\Models\Quiz;
use App\Models\ResultTest;
use App\Models\Room;
use App\Notifications\VerifyEmailNotifications;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens;

    /**
     *
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
        'email_verified_at' => 'datetime',
    ];

    public function quizzes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Quiz::class, 'user_id');
    }

    public function rooms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Room::class, 'user_id');
    }

    public function resultTests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ResultTest::class, 'user_id');
    }

    public function roomsOnline(): int
    {
        return Room::query()->where('user_id', auth()->id())->where('status', 1)->count();
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->name = strtoupper($model->name);
        });
    }
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotifications($this->name));
    }

}
