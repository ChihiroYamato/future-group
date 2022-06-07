<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotebookRequest extends FormRequest
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
        $rules = [
            'name' => 'string|min:3',
            'phone' => 'string|regex:/^\+?[7-8]?\(?9\d{2}\)?\-?\d{3}\-?\d{2}\-?\d{2}$/',
            'email' => 'string|regex:/^[^@]+@\w{2,}\.\w{2,}$/',
            'company' => 'string|min:2',
            'birth_date' => 'date',
        ];

        switch(true) {
            case in_array($this->getMethod(), ['POST', 'PUT']):
                $rules['name'] = 'required|' . $rules['name'];
                $rules['phone'] = 'required|' . $rules['phone'];
                $rules['email'] = 'required|' . $rules['email'];

                return $rules;
            case $this->getMethod() === 'PATCH':
                return $rules;
            default:
                return [];
        }
    }
}
