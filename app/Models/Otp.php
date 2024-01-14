<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Otp extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function verifiable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__);
    }
}
