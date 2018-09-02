<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreTest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'testOptions.name' => 'required|min:4',
            'testOptions.tags' => 'required',
            'testOptions.answersPublic' => 'required|boolean',
            'testOptions.passingPublic' => 'required|boolean',
            'testOptions.duration' => 'required|integer',
            'testOptions.description' => 'required',
            'testOptions.questions.*.id' => 'required|integer',
            'testOptions.questions.*.question' => 'required',
            'testOptions.questions.*.points' => 'required|integer',
            'testOptions.questions.*.typeAnswer' => ['required', 'regex:/^(oneAnswer|someAnswers|number|text)$/iu'],
            'testOptions.questions.*.answers.*.answer' => 'required',
            'testOptions.questions.*.answers.*.correct' => 'required|boolean',
        ];
    }

}
