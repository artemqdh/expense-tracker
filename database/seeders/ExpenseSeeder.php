<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\User;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create expenses for each user
        User::all()->each(function ($user) {
            Expense::factory()->count(5)->create([
                'user_id' => $user->id,
            ]);
        });
    }
}