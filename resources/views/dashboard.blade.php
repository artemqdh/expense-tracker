<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h2 fw-semibold text-dark mb-0">
                    Expense Dashboard
                </h2>
                <p class="text-muted mb-0">Track & manage your expenses</p>
            </div>
        </div>
    </x-slot>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Sticky footer solution */
        html, body {
            height: 100%;
            margin: 0;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* Wrapper to manage flex layout */
        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
        }
        
        /* Main content area - grows to fill space */
        .main-content {
            flex: 1;
            padding-bottom: 80px;
        }
        
        /* Footer - always at bottom */
        .sticky-footer {
            margin-top: auto;
            width: 100%;
            background: white;
        }
        
        /* Custom card styling */
        .card-custom {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        /* Category badge styles */
        .badge-food { background-color: #fee2e2; color: #991b1b; }
        .badge-transport { background-color: #dbeafe; color: #1e40af; }
        .badge-shopping { background-color: #f3e8ff; color: #6b21a8; }
        .badge-entertainment { background-color: #fce7f3; color: #9d174d; }
        .badge-bills { background-color: #dcfce7; color: #166534; }
        .badge-other { background-color: #f3f4f6; color: #374151; }
        
        /* Category progress bar colors */
        .progress-food { background-color: #ef4444; }
        .progress-transport { background-color: #3b82f6; }
        .progress-shopping { background-color: #8b5cf6; }
        .progress-entertainment { background-color: #ec4899; }
        .progress-bills { background-color: #10b981; }
        .progress-other { background-color: #6b7280; }
        
        /* Hover effects for list items */
        .expense-item:hover {
            background-color: #f8f9fa;
            transition: background-color 0.2s;
        }
        
        /* Quick action hover effects */
        .quick-action:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1 !important;
            transition: all 0.2s;
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
        
        /* Custom text sizes */
        .amount-large {
            font-size: 2rem;
            font-weight: 700;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .main-content {
                padding-bottom: 120px;
            }
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

    <div class="page-wrapper">
        <div class="main-content py-5">
            <div class="container">
                <div class="row g-4">
                    
                    <!-- Column 1: Stats -->
                    <div class="col-lg-4">
                        <div class="d-flex flex-column gap-4">
                            <!-- Monthly Total -->
                            <div class="card-custom bg-white p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="h5 fw-semibold text-dark mb-0">Monthly Total</h3>
                                    <i class="fas fa-dollar-sign text-primary"></i>
                                </div>
                                <p class="amount-large text-danger mb-1">${{ number_format($monthlyTotal, 2) }}</p>
                                <p class="text-muted small">{{ date('F Y') }}</p>
                            </div>

                            <!-- Transactions Count -->
                            <div class="card-custom bg-white p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="h5 fw-semibold text-dark mb-0">Transactions</h3>
                                    <i class="fas fa-receipt text-success"></i>
                                </div>
                                <p class="amount-large text-dark mb-1">{{ $recentExpenses->count() }}</p>
                                <p class="text-muted small">This month</p>
                            </div>

                            <!-- Average Daily -->
                            <div class="card-custom bg-white p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h3 class="h5 fw-semibold text-dark mb-0">Average Daily</h3>
                                    <i class="fas fa-chart-line text-warning"></i>
                                </div>
                                <p class="amount-large text-dark mb-1">
                                    ${{ number_format($monthlyTotal / max(date('t'), 1), 2) }}
                                </p>
                                <p class="text-muted small">Per day</p>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: Recent Expenses -->
                    <div class="col-lg-4">
                        <div class="card-custom bg-white h-100">
                            <div class="card-header bg-transparent border-bottom px-4 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="h5 fw-semibold text-dark mb-0">Recent Expenses</h3>
                                    <a href="{{ route('expenses.index') }}" class="text-decoration-none text-primary">
                                        View all
                                    </a>
                                </div>
                            </div>
                            
                            <div class="card-body p-4">
                                @if($recentExpenses->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentExpenses as $expense)
                                    <div class="expense-item list-group-item border-0 px-0 py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <span class="badge rounded-pill d-inline-flex align-items-center 
                                                    @if($expense->category == 'Food') badge-food
                                                    @elseif($expense->category == 'Transport') badge-transport
                                                    @elseif($expense->category == 'Shopping') badge-shopping
                                                    @elseif($expense->category == 'Entertainment') badge-entertainment
                                                    @elseif($expense->category == 'Bills') badge-bills
                                                    @else badge-other @endif
                                                    px-3 py-1 me-3">
                                                    {{ $expense->category }}
                                                </span>
                                                <div class="flex-grow-1">
                                                    <p class="fw-medium text-dark mb-1">
                                                        {{ $expense->description ?: 'No description' }}
                                                    </p>
                                                    <p class="text-muted small mb-0">{{ $expense->date->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <p class="fw-bold text-danger mb-0">-${{ number_format($expense->amount, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-4">
                                    <p class="text-muted mb-2">No expenses recorded yet</p>
                                    <a href="{{ route('expenses.create') }}" class="text-decoration-none text-primary">
                                        Add your first expense
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Column 3: Category Breakdown & Quick Actions -->
                    <div class="col-lg-4">
                        <div class="d-flex flex-column gap-4">
                            <!-- Category Breakdown -->
                            <div class="card-custom bg-white p-4">
                                <h3 class="h5 fw-semibold text-dark mb-4">Spending by Category</h3>
                                
                                @if(!empty($categoryTotals))
                                <div class="space-y-3">
                                    @foreach($categoryTotals as $category => $total)
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fw-medium text-dark">{{ $category }}</span>
                                            <span class="fw-semibold text-dark">${{ number_format($total, 2) }}</span>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            @php
                                                $maxTotal = max($categoryTotals);
                                                $percentage = $maxTotal > 0 ? ($total / $maxTotal) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar 
                                                @if($category == 'Food') progress-food
                                                @elseif($category == 'Transport') progress-transport
                                                @elseif($category == 'Shopping') progress-shopping
                                                @elseif($category == 'Entertainment') progress-entertainment
                                                @elseif($category == 'Bills') progress-bills
                                                @else progress-other @endif" 
                                                role="progressbar" 
                                                style="width: {{ $percentage }}%" 
                                                aria-valuenow="{{ $percentage }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-4">
                                    <p class="text-muted small">No spending data available</p>
                                </div>
                                @endif
                            </div>

                            <!-- Quick Actions -->
                            <div class="card-custom bg-white p-4">
                                <h3 class="h5 fw-semibold text-dark mb-4">Quick Actions</h3>
                                <div class="d-flex flex-column gap-3">
                                    <a href="{{ route('expenses.create') }}" 
                                       class="quick-action d-flex align-items-center p-3 border rounded text-decoration-none">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-plus text-primary"></i>
                                        </div>
                                        <div>
                                            <p class="fw-medium text-dark mb-1">Add Expense</p>
                                            <p class="text-muted small mb-0">Record new spending</p>
                                        </div>
                                    </a>

                                    <a href="{{ route('expenses.index') }}" 
                                       class="quick-action d-flex align-items-center p-3 border rounded text-decoration-none">
                                        <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-list text-success"></i>
                                        </div>
                                        <div>
                                            <p class="fw-medium text-dark mb-1">All Expenses</p>
                                            <p class="text-muted small mb-0">View full history</p>
                                        </div>
                                    </a>

                                    <a href="{{ route('expenses.export.csv') }}" 
                                       class="quick-action d-flex align-items-center p-3 border rounded text-decoration-none">
                                        <div class="bg-purple bg-opacity-10 p-2 rounded me-3">
                                            <i class="fas fa-download text-purple"></i>
                                        </div>
                                        <div>
                                            <p class="fw-medium text-dark mb-1">Export CSV</p>
                                            <p class="text-muted small mb-0">Download report</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer border-top py-4">
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

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</x-app-layout>