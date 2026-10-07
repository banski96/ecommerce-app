<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cart_items' => ['required', 'array', 'min:1'],
            'cart_items.*' => ['required', 'integer'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'max:30'],
        ];
    }
}
