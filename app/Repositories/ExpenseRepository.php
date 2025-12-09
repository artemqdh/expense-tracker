<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use Illuminate\Support\Collection;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    protected $model;

    public function __construct(Expense $model)
    {
        $this->model = $model;
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
        
        return $query->orderBy('date', 'desc')->get();
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
        return $this->model->findOrFail($id)->update($data);
    }

    public function deleteExpense(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function getMonthlyTotal(int $userId, string $month): float
    {
        return $this->model->where('user_id', $userId)
            ->whereMonth('date', $month)
            ->sum('amount');
    }
}