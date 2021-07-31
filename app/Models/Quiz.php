<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Quiz extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ["id", "title", "user_id"];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Question::class, 'quiz_id');
    }

    public function quizCopies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(QuizCopy::class, "quiz_id");
    }
}
