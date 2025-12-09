<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        $categories = ['Food', 'Transport', 'Shopping', 'Entertainment', 'Bills', 'Other'];
        
        return [
            'user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 1, 500),
            'category' => $this->faker->randomElement($categories),
            'date' => $this->faker->dateBetween('-30 days', 'now'),
            'description' => $this->faker->sentence(),
        ];
    }
}