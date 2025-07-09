<?php

namespace Modules\Expenses\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'id' => 'required|exists:expenses,id',
            'title' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric|min:1.00|max:999999.99',
            'category' => [
                'sometimes',
                Rule::in(['Groceries', 'Transportation', 'Health'])
            ],
            'expense_date' => 'sometimes|date',
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
            'id' => [
                'description' => 'The id of the expense to be updated.',
                'example' => '0197e96e-2e7d-7035-b741-dd3d0551b675',
                'required' => true,
            ],
            'title' => [
                'description' => 'The title of the expense.',
                'example' => 'Monthly Subscription',
                'required' => false,
            ],
            'amount' => [
                'description' => 'The amount of the expense. Must be a numeric value between 1.00 and 999999.99.',
                'example' => 29.99,
                'required' => false,
            ],
            'category' => [
                'description' => 'The category of the expense. Must be one of: Groceries, Transportation, Health.',
                'example' => 'Transportation',
                'required' => false,
            ],
            'expense_date' => [
                'description' => 'The date the expense occurred. Format: YYYY-MM-DD.',
                'example' => '2025-07-15',
                'required' => false,
            ],
            'notes' => [
                'description' => 'Any additional notes or details for the expense (optional).',
                'example' => 'Subscription for streaming service.',
                'required' => false,
            ],
        ];
    }
}
