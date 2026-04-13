<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
        'category_id',
        'name',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
