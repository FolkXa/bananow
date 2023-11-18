<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|unique:users|min:8|max:20',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8|max:20',
            'phone' => 'unique:users|min:10|max:15',
            'role' => 'nullable|in:admin,chef,staff,customer',
            'imgPath' => 'nullable|string',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'role' => $this->input('role', 'customer'), // Set 'customer' as default role if not provided
        ]);
    }
}
