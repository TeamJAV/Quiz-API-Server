<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ResultTest extends Model
{
    //
    public function room(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

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

    public function resultDetail(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ResultDetail::class, 'result_id');
    }
}
