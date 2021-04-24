<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quizCopy1(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(QuizCopy1::class, 'quiz1_id');
    }

    public function quizCopy2(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(QuizCopy2::class, 'quiz2_id');
    }

    public function resultTests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ResultTest::class, 'room_id');
    }
}
