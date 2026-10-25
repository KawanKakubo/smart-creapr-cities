<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopResponse extends Model
{
    protected $fillable = ['municipality', 'population_band', 'session', 'answers'];

    protected function casts(): array
    {
        return ['answers' => 'array'];
    }
}
