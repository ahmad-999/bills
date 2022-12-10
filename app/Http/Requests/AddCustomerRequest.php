<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class AddCustomerRequest extends FormRequest
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
        return [
            "name" => 'required|min:3|unique:customers',
            'phone' => 'required|min:10|unique:customers'
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "name is required",
            "name.min" => "name is must be have more then 3 chars.",
            "name.unique" => "customer name must be unique.",
            "phone.required" => "phone is required",
            "phone.min" => "phone is must be have more then 10 chars.",
            "phone.unique" => "customer phone must be unique.",

        ];
    }
    public function failedValidation(ValidationValidator $validator)
    {
        //write your bussiness logic here otherwise it will give same old JSON response
        throw new HttpResponseException(response()->json($validator->errors(), 301));
    }
}
