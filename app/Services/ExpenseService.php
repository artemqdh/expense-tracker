<?php

namespace App\Services;

use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use App\Services\Interfaces\ExpenseServiceInterface;
use App\Models\Expense;
use Illuminate\Support\Collection;

class ExpenseService implements ExpenseServiceInterface
{
    protected $expenseRepository;

    public function __construct(ExpenseRepositoryInterface $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function getUserExpenses(int $userId, array $filters = []): Collection
    {
        return $this->expenseRepository->getUserExpenses($userId, $filters);
    }

    public function getExpense(int $id): ?Expense
    {
        return $this->expenseRepository->getExpense($id);
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
        return $this->expenseRepository->updateExpense($id, $data);
    }

    public function deleteExpense(int $id): bool
    {
        return $this->expenseRepository->deleteExpense($id);
    }

    public function getMonthlyTotal(int $userId, string $month): float
    {
        return $this->expenseRepository->getMonthlyTotal($userId, $month);
    }

    public function exportToCsv(int $userId, array $filters = []): string
    {
        $expenses = $this->getUserExpenses($userId, $filters);
        
        $csv = "Date,Category,Amount,Description\n";
        
        foreach ($expenses as $expense) {
            $csv .= sprintf(
                "%s,%s,%.2f,%s\n",
                $expense->date->format('Y-m-d'),
                $expense->category,
                $expense->amount,
                $expense->description ?? ''
            );
        }
        
        return $csv;
    }
}