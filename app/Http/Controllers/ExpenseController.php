<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Services\Interfaces\ExpenseServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseServiceInterface $expenseService)
    {
        $this->expenseService = $expenseService;
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of expenses.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['category', 'month', 'year']);
        $userId = Auth::id();
        
        $expenses = $this->expenseService->getUserExpenses($userId, $filters);
        
        // Get current month total (формат Y-m)
        $currentMonth = Carbon::now()->format('Y-m');
        $monthlyTotal = $this->expenseService->getMonthlyTotal($userId, $currentMonth);
        
        $categoryTotals = $this->expenseService->getCategoryTotals($userId);
        
        $recentExpenses = $this->expenseService->getRecentExpenses($userId, 5);
        
        $categories = ['Food', 'Transport', 'Shopping', 'Entertainment', 'Bills', 'Other'];

        return view('expenses.index', compact(
            'expenses', 
            'monthlyTotal', 
            'categories', 
            'filters',
            'categoryTotals',
            'recentExpenses'
        ));
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
        
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit($id)
    {
        $expense = $this->expenseService->getExpense($id);
        $categories = ['Food', 'Transport', 'Shopping', 'Entertainment', 'Bills', 'Other'];
        
        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified expense.
     */
    public function update(UpdateExpenseRequest $request, $id)
    {
        $this->expenseService->updateExpense($id, $request->validated());
        
        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified expense.
     */
    public function destroy($id)
    {
        $this->expenseService->deleteExpense($id);
        
        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    /**
     * Show dashboard
     */
    public function dashboard()
    {
        $userId = auth()->id();
        
        $recentExpenses = $this->expenseService->getRecentExpenses($userId, 10);
        
        // Get current month total
        $currentMonth = now()->format('Y-m');
        $monthlyTotal = $this->expenseService->getMonthlyTotal($userId, $currentMonth);
        
        $categoryTotals = $this->expenseService->getCategoryTotals($userId);
        
        // Get expenses by month for chart
        $monthlyExpenses = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $monthlyExpenses[$month] = $this->expenseService->getMonthlyTotal($userId, $month);
        }
        
        return view('dashboard', compact(
            'recentExpenses',
            'monthlyTotal',
            'categoryTotals',
            'monthlyExpenses'
        ));
    }

    /**
     * Filter expenses by category
     */
    public function filterByCategory(Request $request, $category)
    {
        $validCategories = ['Food', 'Transport', 'Shopping', 'Entertainment', 'Bills', 'Other'];
        if (!in_array($category, $validCategories)) {
            abort(404);
        }
        
        $userId = Auth::id();
        
        $expenses = $this->expenseService->getExpensesByCategory($userId, $category);
        
        // Get current month total
        $currentMonth = Carbon::now()->format('Y-m');
        $monthlyTotal = $this->expenseService->getMonthlyTotal($userId, $currentMonth);
        
        $categoryTotals = $this->expenseService->getCategoryTotals($userId);
        
        $categories = $validCategories;
        $filters = ['category' => $category];
        
        return view('expenses.index', compact(
            'expenses', 
            'monthlyTotal', 
            'categories', 
            'filters',
            'categoryTotals',
            'category'
        ));
    }

    /**
     * Filter expenses by month
     */
    public function filterByMonth(Request $request, $month)
    {
        // Validate month format (1-12)
        if (!preg_match('/^(0?[1-9]|1[0-2])$/', $month)) {
            abort(404);
        }
        
        $userId = Auth::id();
        $filters = ['month' => $month];
        
        $expenses = $this->expenseService->getUserExpenses($userId, $filters);
        
        // Calculate total for filtered month
        $year = Carbon::now()->year;
        $monthTotal = $this->expenseService->getMonthlyTotal($userId, "{$year}-{$month}");
        
        $categoryTotals = $this->expenseService->getCategoryTotals($userId);
        $categories = ['Food', 'Transport', 'Shopping', 'Entertainment', 'Bills', 'Other'];
        
        return view('expenses.index', compact(
            'expenses', 
            'monthTotal',
            'categories', 
            'filters',
            'categoryTotals',
            'month'
        ));
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