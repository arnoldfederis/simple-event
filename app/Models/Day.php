<?php

namespace App\Models;

use App\Traits\DateFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;
    use DateFormat;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
