<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductStoreRequest extends FormRequest
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
            'category_id' => 'nullable|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'productVariants.*.attribute_id' => 'nullable|exists:attributs,id',
            'productVariants.*.attribute_option_id' => 'nullable|exists:attribut_options,id',
            'productVariants.*.offer_id' => 'nullable|exists:offers,id',
            'productVariants.*.qty' => 'nullable|integer|min:0',
            'productVariants.*.sku' => 'nullable|string|max:255',
            'productVariants.*.price' => 'nullable|numeric|min:0',
            'productVariants.*.original_price' => 'nullable|numeric|min:0',
            'productVariants.*.off_percentage' => 'nullable|numeric|between:0,100',
            'productVariants.*.variantImages.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 422,
            'message' => $validator->errors()->first(),
        ], 422));
    }
}
