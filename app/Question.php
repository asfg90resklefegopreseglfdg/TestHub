<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Collection;

class Question extends Model
{
    protected $fillable = [
        'question', 'points', 'type_answer', 'test_id'
    ];

    public const TYPE_ANSWER_ONE_ANSWER = 'oneAnswer';
    public const TYPE_ANSWER_SOME_ANSWERS = 'someAnswers';
    public const TYPE_ANSWER_NUMBER = 'number';
    public const TYPE_ANSWER_TEXT = 'text';

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * @param array $answers
     */
    public function addAnswers(array $answers): void
    {
        foreach ($answers as $answerAttributes) {
            $this->addAnswer($answerAttributes);
        }
    }

    /**
     * @param array $answerAttributes
     * @return Answer
     */
    public function addAnswer(array $answerAttributes): Answer
    {
        return Answer::create([
            'answer' => $answerAttributes['answer'],
            'correct' => $answerAttributes['correct'],
            'question_id' => $this->id
        ]);
    }


    /**
     * @return string
     */
    public function getTypeAnswer(): string
    {
        return $this->type_answer;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }


}
