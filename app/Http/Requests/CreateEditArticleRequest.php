<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class CreateEditArticleRequest extends FormRequest
{
    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData() {
        return $this->get('article') ?: [];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'body' => 'required|string',
            'tags' => 'sometimes|array',
        ];
    }
}