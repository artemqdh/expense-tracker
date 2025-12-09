<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends StoreExpenseRequest
{
    public function rules(): array
    {
        return parent::rules();
    }
}