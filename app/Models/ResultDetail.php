<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultDetail extends Model
{
    //
    use SoftDeletes;
    
    public function resultTest(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ResultTest::class, 'result_id');
    }
}
