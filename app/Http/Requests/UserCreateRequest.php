<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{

    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', new AlphaName],
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
        ];
    }
}