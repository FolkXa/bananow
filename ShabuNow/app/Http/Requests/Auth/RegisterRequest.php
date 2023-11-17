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
            'contacts' => 'nullable',
            'role' => 'nullable|in:admin,chef,staff,customer',
            'imgPath' => 'nullable|string',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
//            'username' => ['required','string','unique:users,username'],
//            'email' => ['required','email','unique:users,email'],
//            'firstname' => ['required','string','max:255'],
//            'surname' => ['required','string','max:255'],
//            'password' => ['required','confirmed','min:8'],
//
        ];
    }

    public function messages(): array
    {
        return [
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }

//    public function attributes(): array
//    {
//        return [
//            'password_confirmation' => 'password confirmation',
//        ];
//    }

    protected function prepareForValidation(): void
    {
        // If 'photos' is a file, you may need to handle it differently, for example, using 'image' validation rule
        // This assumes 'photos' is a string, adjust accordingly
        $this->merge([
            'role' => $this->input('role', 'customer'), // Set 'customer' as default role if not provided
        ]);
    }
}
