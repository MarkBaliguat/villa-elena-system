<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()->userID; // Use userID instead of id
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s_-]+$/', // allows letters, numbers, spaces, dashes and underscores
                Rule::unique('users', 'username')->ignore($userId, 'userID'),
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId, 'userID'),
            ],
            'phoneNumber' => [
                'nullable',
                'string',
                'max:20',
               // Rule::unique('users', 'phoneNumber')->ignore($userId, 'userID'),
            ],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.regex' => 'The username may only contain letters, numbers, spaces, dashes, and underscores.',
            'username.unique' => 'The username has already been taken.',
            'email.unique' => 'The email has already been taken.',
          //  'phoneNumber.unique' => 'The phone number has already been taken.',
        ];
    }
}