<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class StoreProductRequest extends FormRequest
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
    public function rules(Request $request)
    {

        $rules = [
            'name' => ['required', Rule::unique('products')->ignore($this->product)],
            'price'  => ['required', 'numeric'],
            'description'  => ['required'],
            'category'  => ['required']
        ];

        if (!$request?->product?->id) {
            foreach ($rules as $key => $value) {
                $rules["*.${key}"] = $value;
                unset($rules[$key]);
            }
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()], 422);

        if (!is_array(current($this->request->all()))) {
            $response = new Response(['error' => 'Product to insert needs to be in array'], 422);
        }
        throw new ValidationException($validator, $response);
    }
}
