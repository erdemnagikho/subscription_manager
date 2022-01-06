<?php

namespace App\Http\Requests;

use App\Rules\DeviceCheck;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DeviceRequest extends FormRequest
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
            'uid' => ['required', 'string', 'min:36', 'max:36', new DeviceCheck($this->app_id)],
            'app_id' => 'required|int|exists:applications,id',
            'language' => 'required|string',
            'os' => 'required|string|in:Android,Ios',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'uid.required' => 'uid can not be blank',
            'uid.min' => 'uid must be at least 36 characters',
            'uid.max' => 'uid must be a maximum of 36 characters',
            'app_id.required' => 'App id can not be blank',
            'language.required' => 'Language can not be blank',
            'os.required' => 'Operation system can not be blank',
        ];
    }
}
