<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    protected $model;

    public function __construct(Expense $expense)
    {
        $this->model = $expense;
    }

    public function getUserExpenses(int $userId, array $filters = []): Collection
    {
        $query = $this->model->where('user_id', $userId);
        
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        
        if (!empty($filters['month'])) {
            $query->whereMonth('date', $filters['month']);
        }
        
        if (!empty($filters['year'])) {
            $query->whereYear('date', $filters['year']);
        }
        
        if (!empty($filters['search'])) {
            $query->where('description', 'like', '%' . $filters['search'] . '%');
        }
        
        return $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->get();
    }

    public function getExpense(int $id): ?Expense
    {
        return $this->model->find($id);
    }

    public function createExpense(array $data): Expense
    {
        return $this->model->create($data);
    }

    public function updateExpense(int $id, array $data): bool
    {
        $expense = $this->getExpense($id);
        if (!$expense) {
            return false;
        }
        
        return $expense->update($data);
    }

    public function deleteExpense(int $id): bool
    {
        $expense = $this->getExpense($id);
        if (!$expense) {
            return false;
        }
        
        return $expense->delete();
    }

    public function getMonthlyTotal(int $userId, string $month): float
    {
        [$year, $month] = explode('-', $month);
        
        return (float) $this->model->where('user_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('amount');
    }
}