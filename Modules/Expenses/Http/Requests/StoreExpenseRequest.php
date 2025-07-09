<?php

namespace Modules\Expenses\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1|max:999999.99',
            'category' => [
                'required',
                Rule::in(['Groceries', 'Transportation', 'Health'])
            ],
            'expense_date' => 'required|date',
            'notes' => 'nullable|string|max:1000'
        ];
    }


    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for the expense',
            'amount.min' => 'The amount must be at least :min',
            'category.in' => 'Please select a valid category',
        ];
    }



    /**
     * Body parameters for Scribe documentation.
     *
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'title' => [
                'description' => 'The title of the expense.',
                'example' => 'Monthly Subscription',
                'required' => true,
            ],
            'amount' => [
                'description' => 'The amount of the expense. Must be a numeric value between 1.00 and 999999.99.',
                'example' => 29.99,
                'required' => true,
            ],
            'category' => [
                'description' => 'The category of the expense. Must be one of: Groceries, Transportation, Health.',
                'example' => 'Transportation',
                'required' => true,
            ],
            'expense_date' => [
                'description' => 'The date the expense occurred. Format: YYYY-MM-DD.',
                'example' => '2025-07-15',
                'required' => true,
            ],
            'notes' => [
                'description' => 'Any additional notes or details for the expense (optional).',
                'example' => 'Subscription for streaming service.',
                'required' => false,
            ],
        ];
    }
}
