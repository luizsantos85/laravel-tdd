<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateUserFormRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            // 'email' => ['sometimes', 'email', \Illuminate\Validation\Rule::unique('users')->ignore($this->route('user'))], // Se quiser permitir alteração do email
            'password' => 'sometimes|required|string|min:6|max:15',
        ];
    }
}
