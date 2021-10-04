<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BusinessModelRequest extends FormRequest
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
          'business_id' => 'required|int',
          'user_id'     => [
            'exists:users,id',
            Rule::requiredIf(fn () => !$this->email)
          ],
          'email'   => 'email',
        ];
    }
}
