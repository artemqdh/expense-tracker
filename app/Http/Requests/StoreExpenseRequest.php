<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'category' => 'required|string|max:50',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Please enter an amount.',
            'amount.min' => 'Amount must be greater than 0.',
            'category.required' => 'Please select a category.',
            'date.required' => 'Please select a date.',
            'date.date' => 'Please enter a valid date.',
        ];
    }
}