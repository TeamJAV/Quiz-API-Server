<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    //

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Question::class, 'quiz_id');
    }
}
