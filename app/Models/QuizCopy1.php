<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class QuizCopy1 extends Model
{
    //

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questionsCopy1s(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QuestionsCopy1::class, 'quiz1_id');
    }

    public function resultTests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ResultTest::class, 'quiz1_id');
    }

    public function rooms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Room::class, 'quiz1_id');
    }
}
