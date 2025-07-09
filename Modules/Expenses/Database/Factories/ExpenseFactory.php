<?php

namespace Modules\Expenses\Database\Factories;

use Modules\Expenses\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'amount' => $this->faker->randomFloat(2, 1, 999999.99),
            'category' => $this->faker->randomElement(['Groceries', 'Transportation', 'Health']),
            'expense_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'notes' => $this->faker->boolean(50) ? $this->faker->paragraph(2) : null,
        ];
    }
}
