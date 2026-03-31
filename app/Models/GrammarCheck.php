<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrammarCheck extends Model
{
    protected $fillable = [
        'original_text',
        'corrected_text',
        'explanation',
    ];
}
