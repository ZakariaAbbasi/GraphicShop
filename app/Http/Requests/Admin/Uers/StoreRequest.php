<?php

namespace App\Http\Requests\Admin\Uers;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'fullName' => 'required|min:3|string',
            'email' => 'required|min:5|email|unique:users,email',
            'mobile' => 'required|digits:11',
            'role' => 'required|in:user,admin',
            'password' => 'required|min:5',
            'passwordRepeat' => 'required|min:5'
        ];
    }
}
