<x-app-layout>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* SOLUTION 1: Simple fixed footer approach */
        html, body {
            height: 100%;
            margin: 0;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* This wrapper takes all available space */
        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
        }
        
        /* Main content area - grows to fill space */
        .main-content {
            flex: 1;
            padding-bottom: 80px; /* Space for footer */
        }
        
        /* Footer - ALWAYS at bottom */
        .sticky-footer {
            margin-top: auto; /* This pushes it to the bottom */
            background: white;
            border-top: 1px solid #dee2e6;
            width: 100%;
        }
        
        /* Alternative: Fixed bottom approach (uncomment if needed)
        .sticky-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 100;
        }
        
        .main-content {
            padding-bottom: 150px;  // Height of footer
        }
        */
        
        /* Category badge styles */
        .badge-food { background-color: #fee2e2; color: #991b1b; }
        .badge-transport { background-color: #dbeafe; color: #1e40af; }
        .badge-shopping { background-color: #f3e8ff; color: #6b21a8; }
        .badge-entertainment { background-color: #fce7f3; color: #9d174d; }
        .badge-bills { background-color: #dcfce7; color: #166534; }
        .badge-other { background-color: #f3f4f6; color: #374151; }
        
        /* Button styles */
        .btn-edit { background-color: #dbeafe; color: #1d4ed8; border: none; }
        .btn-edit:hover { background-color: #bfdbfe; color: #1e40af; }
        
        .btn-delete { background-color: #fee2e2; color: #dc2626; border: none; }
        .btn-delete:hover { background-color: #fecaca; color: #b91c1c; }
        
        .btn-export { background-color: #ffffff; color: #374151; border: 1px solid #d1d5db; }
        .btn-export:hover { background-color: #f9fafb; }
        
        .btn-add { background-color: #2563eb; color: white; border: none; }
        .btn-add:hover { background-color: #1d4ed8; }
        
        .filter-btn { 
            padding: 6px 12px; 
            border-radius: 50px; 
            font-size: 14px; 
            text-decoration: none;
            display: inline-block;
        }
        
        .filter-active { 
            background-color: #dbeafe; 
            color: #1e40af;
        }
        
        .filter-inactive { 
            background-color: #f3f4f6; 
            color: #374151;
        }
        
        .filter-inactive:hover { 
            background-color: #e5e7eb; 
        }
        
        .expense-amount { 
            color: #dc2626; 
            font-weight: 600; 
        }
        
        /* Table styling */
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .table td {
            vertical-align: middle;
            padding: 20px 16px;
        }
        
        .table-hover tbody tr:hover {
            background-color: #f9fafb;
        }
        
        /* Footer styling */
        .footer-gradient {
            background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 10px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
        }
        
        .footer-link {
            color: #6b7280;
            text-decoration: none;
        }
        
        .footer-link:hover {
            color: #4f46e5;
        }
        
        /* Empty state */
        .empty-state {
            padding: 80px 20px;
        }
        
        .empty-state-icon {
            font-size: 64px;
            color: #d1d5db;
        }
    </style>

    <!-- START: New wrapper div for sticky footer -->
    <div class="page-wrapper">
        
        <div class="main-content py-5">
            <div class="container">
                <!-- Header -->
                <div class="mb-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 fw-bold text-dark mb-1">All Expenses</h1>
                            <p class="text-muted mb-0">Manage and track your spending</p>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="{{ route('expenses.export.csv') }}" 
                               class="btn btn-export d-flex align-items-center gap-2">
                                <i class="fas fa-download"></i>
                                Export CSV
                            </a>
                            <a href="{{ route('expenses.create') }}" 
                               class="btn btn-add d-flex align-items-center gap-2">
                                <i class="fas fa-plus"></i>
                                Add Expense
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded border p-4 mb-5">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('expenses.index') }}" 
                           class="filter-btn {{ !request()->has('category') ? 'filter-active' : 'filter-inactive' }}">
                            All
                        </a>
                        @foreach(['Food', 'Transport', 'Shopping', 'Entertainment', 'Bills', 'Other'] as $category)
                        <a href="{{ route('expenses.filter.category', $category) }}" 
                           class="filter-btn {{ request('category') == $category ? 'filter-active' : 'filter-inactive' }}">
                            {{ $category }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Expenses Table -->
                <div class="bg-white rounded border overflow-hidden">
                    @if($expenses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3">Date</th>
                                    <th class="px-3 py-3">Category</th>
                                    <th class="px-3 py-3">Description</th>
                                    <th class="px-3 py-3">Amount</th>
                                    <th class="px-3 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                <tr>
                                    <td class="ps-4">
                                        <span class="text-dark">{{ $expense->date->format('M d, Y') }}</span>
                                    </td>

                                    <td class="px-3">
                                        <span class="badge rounded-pill d-inline-flex align-items-center 
                                            @if($expense->category == 'Food') badge-food
                                            @elseif($expense->category == 'Transport') badge-transport
                                            @elseif($expense->category == 'Shopping') badge-shopping
                                            @elseif($expense->category == 'Entertainment') badge-entertainment
                                            @elseif($expense->category == 'Bills') badge-bills
                                            @else badge-other @endif
                                            px-3 py-1">
                                            {{ $expense->category }}
                                        </span>
                                    </td>

                                    <td class="px-3 text-dark">
                                        {{ $expense->description ?: '—' }}
                                    </td>

                                    <td class="px-3 expense-amount">
                                        -${{ number_format($expense->amount, 2) }}
                                    </td>

                                    <td class="px-3">
                                        <div class="d-flex gap-3">
                                            <!-- Edit Button -->
                                            <a href="{{ route('expenses.edit', $expense->id) }}"
                                               class="btn btn-edit px-3 py-2 rounded">
                                                Edit
                                            </a>
                                            <!-- Delete Button -->
                                            <form action="{{ route('expenses.destroy', $expense->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete this expense?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-delete px-3 py-2 rounded">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($expenses->hasPages())
                    <div class="d-flex justify-content-center py-4 border-top">
                        {{ $expenses->links('pagination::bootstrap-5') }}
                    </div>
                    @endif

                    @else
                    <!-- Empty State -->
                    <div class="text-center empty-state">
                        <div class="empty-state-icon mb-3">
                            <i class="far fa-file-alt"></i>
                        </div>
                        <h3 class="h4 fw-bold text-dark mb-2">No expenses</h3>
                        <p class="text-muted mb-4">Get started by creating a new expense.</p>
                        <div>
                            <a href="{{ route('expenses.create') }}" 
                               class="btn btn-add px-4 py-2 rounded">
                                <i class="fas fa-plus me-2"></i>
                                Add Expense
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer - NOW IT WILL STICK TO BOTTOM -->
        <footer class="sticky-footer py-4">
            <div class="container">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="d-flex align-items-center mb-3 mb-md-0">
                        <div class="footer-gradient me-2">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="text-dark fw-medium">ExpenseTracker</span>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <a href="#" class="footer-link">
                            <i class="fab fa-twitter fa-lg"></i>
                        </a>
                        <a href="#" class="footer-link">
                            <i class="fab fa-github fa-lg"></i>
                        </a>
                        <a href="#" class="footer-link">
                            <i class="fab fa-discord fa-lg"></i>
                        </a>
                    </div>
                </div>
                
                <div class="text-center mt-3 text-muted small">
                    © 2025 ExpenseTracker. Helping you achieve financial freedom.
                </div>
            </div>
        </footer>
        
    </div>
    <!-- END: page-wrapper -->

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</x-app-layout>