<?php

namespace App\Http\Requests;

use App\Rules\SubscriptionCheck;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubscriptionRequest extends FormRequest
{
    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'client_token' => ['required', 'string', new SubscriptionCheck()],
            'receipt' => 'required|int',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'client_token.required' => 'Client token can not be blank',
            'client_token.exists' => 'Client token can not be found',
            'receipt.required' => 'Receipt can not be blank',
        ];
    }
}
