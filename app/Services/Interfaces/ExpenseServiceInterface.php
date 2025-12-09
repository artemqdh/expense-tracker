<?php

namespace App\Services\Interfaces;

use App\Models\Expense;
use Illuminate\Support\Collection;

interface ExpenseServiceInterface
{
    public function getUserExpenses(int $userId, array $filters = []): Collection;
    public function getExpense(int $id): ?Expense;
    public function createExpense(array $data): Expense;
    public function updateExpense(int $id, array $data): bool;
    public function deleteExpense(int $id): bool;
    public function getMonthlyTotal(int $userId, string $month): float;
    public function exportToCsv(int $userId, array $filters = []): string;
}