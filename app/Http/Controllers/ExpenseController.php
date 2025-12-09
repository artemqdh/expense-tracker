<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Services\Interfaces\ExpenseServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseServiceInterface $expenseService)
    {
        $this->expenseService = $expenseService;
        
        // Apply middleware - THIS SHOULD WORK
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of expenses.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['category', 'month', 'year']);
        $expenses = $this->expenseService->getUserExpenses(Auth::id(), $filters);
        $monthlyTotal = $this->expenseService->getMonthlyTotal(Auth::id(), date('m'));
        
        $categories = ['Food', 'Transport', 'Shopping', 'Entertainment', 'Bills', 'Other'];

        return view('expenses.index', compact('expenses', 'monthlyTotal', 'categories', 'filters'));
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create()
    {
        $categories = ['Food', 'Transport', 'Shopping', 'Entertainment', 'Bills', 'Other'];
        return view('expenses.create', compact('categories'));
    }

    /**
     * Store a newly created expense.
     */
    public function store(StoreExpenseRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        
        $this->expenseService->createExpense($data);
        
        return redirect()->route('expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    /**
     * Display the specified expense.
     */
    public function show($id)
    {
        $expense = $this->expenseService->getExpense($id);
        
        // Authorization
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit($id)
    {
        $expense = $this->expenseService->getExpense($id);
        
        // Authorization
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        
        $categories = ['Food', 'Transport', 'Shopping', 'Entertainment', 'Bills', 'Other'];
        
        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified expense.
     */
    public function update(UpdateExpenseRequest $request, $id)
    {
        $expense = $this->expenseService->getExpense($id);
        
        // Authorization
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        
        $this->expenseService->updateExpense($id, $request->validated());
        
        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified expense.
     */
    public function destroy($id)
    {
        $expense = $this->expenseService->getExpense($id);
        
        // Authorization
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        
        $this->expenseService->deleteExpense($id);
        
        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    /**
     * Export expenses to CSV
     */
    public function export(Request $request)
    {
        $filters = $request->only(['category', 'month', 'year']);
        $csv = $this->expenseService->exportToCsv(Auth::id(), $filters);
        
        $filename = 'expenses_' . date('Y-m-d') . '.csv';
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}