<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$this->id.',id',
            'password' => ''
        ];

        if($this['_method'] == 'POST'){
            $rules['password'] .= 'required|string|min:8|confirmed';
        }else{
            $rules['password'] .= 'nullable|string|min:8|confirmed';
        }

        return $rules;
    }
}
