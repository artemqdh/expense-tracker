<?php

namespace App\Services\Interfaces;

use App\Models\Expense;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\Paginator;

interface ExpenseServiceInterface
{
    public function getUserExpenses(int $userId, array $filters = []): Paginator;
    public function getExpense(int $id): ?Expense;
    public function createExpense(array $data): Expense;
    public function updateExpense(int $id, array $data): bool;
    public function deleteExpense(int $id): bool;
    public function getMonthlyTotal(int $userId, string $month): float;
    public function getCurrentMonthTotal(int $userId): float;
    public function getExpensesByCategory(int $userId, string $category): Paginator;
    public function getRecentExpenses(int $userId, int $limit = 5): Collection;
    public function getCategoryTotals(int $userId): array;
    public function exportToCsv(int $userId, array $filters = []): string;
}