<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    public const DIFFICULTY_BASIC = 'basic';
    public const DIFFICULTY_ELEMENTARY = 'elementary';
    public const DIFFICULTY_INTERMEDIATE = 'intermediate';
    public const DIFFICULTY_ADVANCED = 'advanced';

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'difficulty',
    ];

    public static function difficultyOptions(): array
    {
        return [
            self::DIFFICULTY_BASIC => 'Basic',
            self::DIFFICULTY_ELEMENTARY => 'Elementary',
            self::DIFFICULTY_INTERMEDIATE => 'Intermediate',
            self::DIFFICULTY_ADVANCED => 'Advanced',
        ];
    }

    public function difficultyLabel(): string
    {
        return self::difficultyOptions()[$this->difficulty] ?? ucfirst((string) $this->difficulty);
    }

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
