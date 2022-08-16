<?php

namespace App\Http\Requests\Auth;

use App\Exceptions\UserExistException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class RegisterUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'country_code' => 'integer|nullable',
            'phone_number' => 'integer|nullable',
            'gender' => Rule::in(['m', 'f']),
            'date_of_birth' => 'nullable|date',
            'password' => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $emailAddress = $this->email;

        // Run after all validation rules passed
        $validator->after(function ($validator) use ($emailAddress) {
            // Check if email already registered
            if (User::where('email', '=', $emailAddress)->count()) {
                throw new UserExistException();
            }
        });

        return;
    }

    public function messages()
    {
        return [
            'gender.in' => "The gender must be m or f"
        ];
    }
}
