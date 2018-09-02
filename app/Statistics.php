<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;

class Statistics extends Model
{
    Use Sortable;

    protected $table = 'statistical_data';

    protected $fillable = [
        'user_id',
        'test_id',
        'test_end_time',
        'test_complete',
        'points'
    ];

    public $sortable = [
        'id',
        'points',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @param \Illuminate\Support\Collection $questions
     * @param array $userAnswers
     * @return int
     */
    public function calculatePoints(\Illuminate\Support\Collection $questions, array $userAnswers): int
    {
        $points = 0;
        foreach ($questions as $question) {
            foreach ($userAnswers as $userAnswer) {
                if ($question->getId() == $userAnswer['questionId']) {
                    if ($question->type_answer === Question::TYPE_ANSWER_SOME_ANSWERS) {
                        $correctAnswersArray = $this->transformCorrectAnswersInOneArray($question);
                        $result = array_diff($userAnswer['answers'], $correctAnswersArray);
                        $secondResult = array_diff($correctAnswersArray, $userAnswer['answers']);
                        if (empty($result) and empty($secondResult)) {
                            $points += $question->getPoints();
                        }
                    } elseif ($question->answers[0]->answer == $userAnswer['answer']) {
                        $points += $question->getPoints();
                    }
                    continue 2;

                }
            }
        }
        return $points;
    }

    /**
     * @param $question
     * @return array
     */
    private function transformCorrectAnswersInOneArray($question)
    {
        $correctAnswers = [];
        foreach ($question->answers as $answer) {
            $correctAnswers[] = $answer->answer;
        }
        return $correctAnswers;
    }


    /**
     * @return string
     */
    public function getTestEndTime(): string
    {
        return $this->test_end_time;
    }

    /**
     * @param array $userAnswers
     * @param Collection $questions
     */
    public function completeTestPassing(array $userAnswers, Collection $questions): void
    {

        $points = $this->calculatePoints($questions, $userAnswers);
        $canUpdate = $this->checkTestEndTime();
        if ($canUpdate and !$this->checkTestComplete()) {
            $this->completeStatistic($points);
        }
    }

    /**
     * @param int $points
     */
    public function completeStatistic(int $points): void
    {
        $this->update([
            'points' => $points,
            'test_complete' => true,
        ]);
        $this->save();

    }

    /**
     * @return bool
     */
    public function checkTestComplete(): bool
    {
        return $this->test_complete;
    }

    /**
     * @return bool
     */
    public function checkTestEndTime(): bool
    {
        $testEndTime = $this->getTestEndTime();
        $time = time();
        $testEndTime = strtotime($testEndTime);
        $canUpdate = $testEndTime - $time;
        $canUpdate = $canUpdate > 1 ? true : false;
        if ($canUpdate) {
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getPoints()
    {
        return $this->points;
    }


}
