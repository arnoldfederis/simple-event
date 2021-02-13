<?php

namespace App\Models;

use App\Traits\DateFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    use DateFormat;

    protected $guarded = [];

    public function days()
    {
        return $this->hasMany(Day::class);
    }
}
