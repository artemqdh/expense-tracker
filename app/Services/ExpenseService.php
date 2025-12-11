<?php

namespace App\Services;

use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use App\Services\Interfaces\ExpenseServiceInterface;
use App\Models\Expense;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ExpenseService implements ExpenseServiceInterface
{
    protected $expenseRepository;

    public function __construct(ExpenseRepositoryInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function getUserExpenses(int $userId, array $filters = []): Paginator
    {
        $expenses = $this->expenseRepository->getUserExpenses($userId, $filters);
        
        // Convert Collection to Paginator
        $page = request()->get('page', 1);
        $perPage = 15;
        $items = $expenses->forPage($page, $perPage);
        
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $expenses->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function getExpense(int $id): ?Expense
    {
        $expense = $this->expenseRepository->getExpense($id);
        
        // Authorization check
        if ($expense && $expense->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return $expense;
    }

    public function createExpense(array $data): Expense
    {
        if ($data['amount'] <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than 0.');
        }

        return $this->expenseRepository->createExpense($data);
    }

    public function updateExpense(int $id, array $data): bool
    {
        // First authorize
        $this->getExpense($id);
        
        return $this->expenseRepository->updateExpense($id, $data);
    }

    public function deleteExpense(int $id): bool
    {
        // First authorize
        $this->getExpense($id);
        
        return $this->expenseRepository->deleteExpense($id);
    }

    public function getMonthlyTotal(int $userId, string $month): float
    {
        return $this->expenseRepository->getMonthlyTotal($userId, $month);
    }

    public function getCurrentMonthTotal(int $userId): float
    {
        $currentMonth = Carbon::now()->format('Y-m');
        return $this->getMonthlyTotal($userId, $currentMonth);
    }

    public function getExpensesByCategory(int $userId, string $category): Paginator
    {
        $filters = ['category' => $category];
        return $this->getUserExpenses($userId, $filters);
    }

    public function getRecentExpenses(int $userId, int $limit = 5): Collection
    {
        return $this->expenseRepository->getUserExpenses($userId, [])
            ->sortByDesc('date')
            ->take($limit);
    }

    public function getCategoryTotals(int $userId): array
    {
        $currentMonth = Carbon::now()->format('Y-m');
        [$year, $month] = explode('-', $currentMonth);
        
        $expenses = $this->expenseRepository->getUserExpenses($userId, [])
            ->filter(function ($expense) use ($year, $month) {
                return $expense->date->year == $year && $expense->date->month == $month;
            });
        
        return $expenses->groupBy('category')
            ->map(function ($items) {
                return $items->sum('amount');
            })
            ->toArray();
    }

    public function exportToCsv(int $userId, array $filters = []): string
    {
        $expenses = $this->expenseRepository->getUserExpenses($userId, $filters);
        
        $csv = "Date,Category,Amount,Description\n";
        
        foreach ($expenses as $expense) {
            $csv .= sprintf(
                "%s,%s,%.2f,%s\n",
                $expense->date->format('Y-m-d'),
                $expense->category,
                $expense->amount,
                str_replace(',', ' ', $expense->description ?? '')
            );
        }
        
        return $csv;
    }
}