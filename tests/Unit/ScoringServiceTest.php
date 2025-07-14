<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Quiz;
use App\Models\Question;
use App\Services\ScoringService;

class ScoringServiceTest extends TestCase
{
    public function test_score_calculation()
    {
        $quiz = new Quiz();
        $quiz->setRelation('questions', collect([
            (object)['id' => 1, 'correct_answer' => 'A', 'points' => 2],
            (object)['id' => 2, 'correct_answer' => 'B', 'points' => 1],
        ]));
        $answers = [1 => 'A', 2 => 'C'];
        $service = new ScoringService();
        $result = $service->calculateScore($quiz, $answers);
        $this->assertEquals(2, $result['score']);
        $this->assertCount(2, $result['details']);
        $this->assertTrue($result['details'][0]['is_correct']);
        $this->assertFalse($result['details'][1]['is_correct']);
    }
}
