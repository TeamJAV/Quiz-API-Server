<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ["name", "shuffle_answer", "shuffle_question", "required_name", "status", "time_offline", "user_id"];
    protected $dates = ['deleted_at'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resultTests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ResultTest::class, 'room_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->name = strtoupper($model->name);
        });
    }

}
