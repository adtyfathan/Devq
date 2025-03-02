<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}

// Laravel Form Requests handle validation and authorization for incoming data before it reaches the controller.

// namespace App\Http\Requests;

// use Illuminate\Foundation\Http\FormRequest;

// class QuizRequest extends FormRequest
// {
//     public function rules(): array
//     {
//         return [
//             'title' => 'required|string|max:255',
//             'description' => 'nullable|string',
//         ];
//     }
// }


// PAKE DI CONTROLLER

// public function store(QuizRequest $request)
// {
//     return Quiz::create($request->validated());
// }