<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'answer', 'correct', 'question_id'
        ];


    public function question()
    {
        return $this->belongsTo(Question::class);
    }


    /**
     * @return bool
     */
    public function checkCorrect(): bool
    {
        if ($this->correct) {
            return true;
        }
        return false;
    }


    /**
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

}
