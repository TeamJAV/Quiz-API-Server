<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultTest extends Model
{
    //
    protected $guarded = [];
    use SoftDeletes;

    public function room(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quizCopy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(QuizCopy::class, "quiz_copy_id");
    }

    public function resultDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ResultDetail::class, 'result_id');
    }
}
