<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class QuizCopy2 extends Model
{
    //
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questionsCopy2s(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QuestionsCopy2::class, 'quiz2_id');
    }

    public function resultTests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ResultTest::class, 'quiz2_id');
    }

    public function rooms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Room::class, 'quiz2_id');
    }
}
