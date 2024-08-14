<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OfferProductFilterRequest extends FormRequest
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
            'id' => 'required_if:type,assign',
            'key' => 'required',
            'filterTypes' => 'nullable|array',
            'filterTypes.*' => 'string|in:brand,category,subcategory,price,date,inventory',
            'dateValue' => 'nullable|array',
            'dateValue.startdate' => 'required_with:dateValue|date',
            'dateValue.enddate' => 'required_with:dateValue|date|after_or_equal:dateValue.startdate',
            'priceValue' => 'nullable|array',
            'priceValue.min' => 'required_with:priceValue|numeric',
            'priceValue.max' => 'required_with:priceValue|numeric',
            'brandValue' => 'nullable|array',
            'brandValue.*' => 'integer|exists:brands,id',
            'categoryValue' => 'nullable|array',
            'categoryValue.*' => 'integer|exists:categories,id',
            'subCategoryValue' => 'nullable|array',
            'subCategoryValue.*' => 'integer|exists:subcategories,id',
            // 'inventoryValue' => 'nullable|array',
            // 'inventoryValue.min' => 'required_if:inventoryValue,!=,null|numeric',
            // 'inventoryValue.max' => 'required_if:inventoryValue,!=,null|numeric',
            // 'inventoryValue.is_sum' => 'required_if:inventoryValue,!=,null|boolean',
        ];
    }

    public function messages()
    {
        return [
            'key.required' => 'The key is required.',
            'filterTypes.required' => 'The filter types are required.',
            'filterTypes.array' => 'The filter types must be an array.',
            'filterTypes.*.in' => 'The filter type must be one of the following: brand, category, subcategory, price, date, inventory.',
            'dateValue.startdate.required_with' => 'The start date is required when date filter is provided.',
            'dateValue.enddate.required_with' => 'The end date is required when date filter is provided.',
            'dateValue.startdate.date' => 'The start date must be a valid date.',
            'dateValue.enddate.date' => 'The end date must be a valid date.',
            'dateValue.enddate.after_or_equal' => 'The end date must be a date after or equal to the start date.',
            'priceValue.min.required_with' => 'The minimum price is required when price filter is provided.',
            'priceValue.max.required_with' => 'The maximum price is required when price filter is provided.',
            'priceValue.min.numeric' => 'The minimum price must be a numeric value.',
            'priceValue.max.numeric' => 'The maximum price must be a numeric value.',
            'brandValue.array' => 'The brand value must be an array.',
            'brandValue.*.integer' => 'Each brand value must be an integer.',
            'brandValue.*.exists' => 'The selected brand value does not exist.',
            'categoryValue.array' => 'The category value must be an array.',
            'categoryValue.*.integer' => 'Each category value must be an integer.',
            'categoryValue.*.exists' => 'The selected category value does not exist.',
            'subCategoryValue.array' => 'The subcategory value must be an array.',
            'subCategoryValue.*.integer' => 'Each subcategory value must be an integer.',
            'subCategoryValue.*.exists' => 'The selected subcategory value does not exist.',
            'inventoryValue.min.required_with' => 'The minimum inventory size is required when inventory filter is provided.',
            'inventoryValue.max.required_with' => 'The maximum inventory size is required when inventory filter is provided.',
            'inventoryValue.is_sum.required_with' => 'The is_sum inventory is required when inventory filter is provided.',
            'inventoryValue.min.numeric' => 'The minimum inventory size must be a numeric value.',
            'inventoryValue.max.numeric' => 'The maximum inventory size must be a numeric value.',
            'inventoryValue.is_sum.boolean' => 'The is_sum inventory must be a boolean value.',
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
