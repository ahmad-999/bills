<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class importRequest extends FormRequest
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
            'payment' => "required|regex:/^\d+(\.\d{1,2})?$/",
            "customer_id" => "required|exists:customers,id",
            "opration_date" => "required"
        ];
    }
    public function messages()
    {
        return [
            "payment.required" => "payment is required",
            "payment.regex" => "payment is must be integer or double.",
            "customer_id.required" => "customer_id is required",
            "customer_id.exists" => "customer_id must be in customers table. ",
            "opration_date.required" => "opration_date is required.",
        ];
    }
    public function failedValidation(ValidationValidator $validator)
    {
        //write your bussiness logic here otherwise it will give same old JSON response
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
