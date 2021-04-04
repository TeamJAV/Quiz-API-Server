<?php

namespace App;

use App\Models\Quiz;
use App\Models\QuizCopy1;
use App\Models\QuizCopy2;
use App\Models\ResultTest;
use App\Models\Room;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable  implements MustVerifyEmail
{
    use Notifiable;

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

    public function quizCopy2s(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QuizCopy2::class, 'user_id');
    }

    public function quizCopy1s(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QuizCopy1::class, 'user_id');
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
}
