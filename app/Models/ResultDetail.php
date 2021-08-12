<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultDetail extends Model
{
    //
    use SoftDeletes;

    protected $guarded = [];

//    protected $fillable = ["student_name", "scores", "student_choices", "time_joined", "time_end", "result_id", "is_finished", "timestamp_out", "room_pending_id"];

    public function resultTest(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ResultTest::class, 'result_id');
    }
}
