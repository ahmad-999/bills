<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class ExportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'wages' => "required|regex:/^\d+(\.\d{1,2})?$/",
            'opration_date' => "required",
            'material_price' => "required|regex:/^\d+(\.\d{1,2})?$/",
            "customer_id" => "required|exists:customers,id"
        ];
    }
    public function messages()
    {
        return [
            "wages.required" => "wages is required",
            "wages.regex" => "wages is must be integer or double.",
            "material_price.required" => "material_price is required",
            "material_price.regex" => "material_price is must be integer or double.",
            "customer_id.required" => "customer_id is required",
            "customer_id.exists" => "customer_id must be in customers table. ",
            "opration_date.required" => "opration_date is required.",
        ];
    }
    public function failedValidation(ValidationValidator $validator) { 
        //write your bussiness logic here otherwise it will give same old JSON response
       throw new HttpResponseException(response()->json($validator->errors(), 422)); 
   }
}
