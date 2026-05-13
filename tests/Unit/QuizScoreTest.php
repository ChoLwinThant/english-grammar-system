<?php

namespace Tests\Unit;

use Tests\TestCase;

class QuizScoreTest extends TestCase
{
    public function test_quiz_score_is_calculated_correctly(): void
    {
        $correctAnswers = [
            1 => 'B',
            2 => 'A',
            3 => 'C',
        ];

        $userAnswers = [
            1 => 'B',
            2 => 'D',
            3 => 'C',
        ];

        $score = 0;

        foreach ($correctAnswers as $questionId => $correctAnswer) {
            if (($userAnswers[$questionId] ?? null) === $correctAnswer) {
                $score++;
            }
        }

        $this->assertEquals(2, $score);
    }
}
