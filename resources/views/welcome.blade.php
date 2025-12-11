<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ExpenseTracker - Smart Tracking</title>

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <style>
            /* Sticky footer and overall layout */
            html, body {
                height: 100%;
                margin: 0;
            }
            
            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #ffffff;
                color: #1f2937;
            }
            
            /* Wrapper for sticky footer */
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
            
            /* Gradient colors */
            .bg-gradient-expense {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            
            .text-gradient-expense {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .bg-gradient-expense-light {
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            }
            
            /* Custom shadow */
            .shadow-soft {
                box-shadow: 0 10px 40px rgba(102, 126, 234, 0.1);
            }
            
            /* Hover effects */
            .hover-scale {
                transition: transform 0.3s ease;
            }
            
            .hover-scale:hover {
                transform: translateY(-5px);
            }
            
            /* Custom button */
            .btn-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
            }
            
            .btn-gradient:hover {
                background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
                color: white;
                opacity: 0.9;
            }
            
            /* Custom border and card */
            .custom-border {
                border: 1px solid #e5e7eb;
                border-radius: 12px;
            }
            
            .feature-icon {
                width: 32px;
                height: 32px;
                background-color: rgba(102, 126, 234, 0.1);
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            
            .feature-icon i {
                color: #667eea;
            }
            
            /* Stat card */
            .stat-card {
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
                border-radius: 12px;
                padding: 20px;
                text-align: center;
            }
            
            .stat-number {
                font-size: 1.75rem;
                font-weight: 700;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            /* Header navigation */
            .nav-link-custom {
                color: #4b5563;
                text-decoration: none;
                padding: 8px 20px;
                border-radius: 6px;
                transition: all 0.2s;
            }
            
            .nav-link-custom:hover {
                background-color: #f3f4f6;
                color: #374151;
            }
            
            .nav-link-custom.btn-outline {
                border: 1px solid #d1d5db;
            }
            
            .nav-link-custom.btn-outline:hover {
                border-color: #9ca3af;
            }
            
            /* Main content area */
            .content-box {
                border: 1px solid #e5e7eb;
                border-radius: 16px;
                background-color: white;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            }
            
            /* Responsive adjustments */
            @media (max-width: 768px) {
                .main-content {
                    padding-bottom: 100px;
                }
                
                .stat-number {
                    font-size: 1.5rem;
                }
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
                font-size: 1.25rem;
            }
            
            .footer-link:hover {
                color: #4f46e5;
            }
            
            /* Animation for content */
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            .fade-in {
                animation: fadeIn 0.75s ease-in-out;
            }
        </style>
    </head>
    <body>
        <!-- Page wrapper for sticky footer -->
        <div class="page-wrapper">
            
            <header class="py-3 py-lg-4">
                <div class="container">
                    @if (Route::has('login'))
                        <nav class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="footer-gradient me-2">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <span class="text-dark fw-bold fs-5 ms-2">
                                    ExpenseTracker
                                </span>
                            </div>
                            
                            <div class="d-flex align-items-center gap-2">
                                @auth
                                    <a href="{{ url('/dashboard') }}"
                                       class="nav-link-custom btn-outline">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                       class="nav-link-custom me-2">
                                        Log in
                                    </a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"
                                           class="nav-link-custom btn-outline">
                                            Get Started
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </nav>
                    @endif
                </div>
            </header>
            
            <div class="main-content fade-in">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-10 col-xl-8">
                            <div class="content-box p-4 p-lg-5 mb-5">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 class="display-5 fw-bold text-dark mb-3">
                                            Take Control of Your Finances
                                        </h1>
                                        <p class="lead text-muted mb-5">
                                            The simplest way to track expenses, set budgets, and achieve your financial goals.
                                        </p>
                                        
                                        <!-- Features List -->
                                        <div class="mb-5">
                                            <div class="row gy-3">
                                                <div class="col-12 col-md-6">
                                                    <div class="d-flex align-items-center p-3 rounded">
                                                        <div class="feature-icon me-3">
                                                            <i class="fas fa-bolt"></i>
                                                        </div>
                                                        <span class="fw-medium text-dark">Track expenses in seconds</span>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="d-flex align-items-center p-3 rounded">
                                                        <div class="feature-icon me-3">
                                                            <i class="fas fa-chart-pie"></i>
                                                        </div>
                                                        <span class="fw-medium text-dark">Visual spending insights</span>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="d-flex align-items-center p-3 rounded">
                                                        <div class="feature-icon me-3">
                                                            <i class="fas fa-shield-alt"></i>
                                                        </div>
                                                        <span class="fw-medium text-dark">Bank-level security</span>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="d-flex align-items-center p-3 rounded">
                                                        <div class="feature-icon me-3">
                                                            <i class="fas fa-sync"></i>
                                                        </div>
                                                        <span class="fw-medium text-dark">Real-time sync across devices</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Stats -->
                                        <div class="row mb-5">
                                            <div class="col-12 col-md-6 mb-4 mb-md-0">
                                                <div class="stat-card hover-scale">
                                                    <div class="stat-number">10,000+</div>
                                                    <div class="text-muted small mt-2">Active Users</div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="stat-card hover-scale">
                                                    <div class="stat-number">$2M+</div>
                                                    <div class="text-muted small mt-2">Tracked Monthly</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            @if (Route::has('register'))
                                                <a href="{{ route('register') }}" 
                                                   class="btn btn-gradient btn-lg w-100 d-flex align-items-center justify-content-center gap-2 py-3 fw-medium shadow-soft hover-scale">
                                                    <i class="fas fa-rocket"></i>
                                                    Start Free Trial
                                                </a>
                                            @endif
                                        </div>
                                        <p class="text-center text-muted small mt-4">
                                            <i class="fas fa-lock me-1"></i>
                                            Secure & Private • No credit card required
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Features Section -->
            <div id="features" class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6 text-center">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="btn btn-gradient btn-lg px-5 py-3 d-inline-flex align-items-center justify-content-center gap-3 fw-bold shadow-soft hover-scale">
                                <i class="fas fa-rocket"></i>
                                Start Your Free Trial Today
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="sticky-footer border-top py-5 mt-5">
                <div class="container">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <div class="d-flex align-items-center mb-4 mb-md-0">
                            <div class="footer-gradient me-2">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span class="text-dark fw-medium">ExpenseTracker</span>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="#" class="footer-link">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="footer-link">
                                <i class="fab fa-github"></i>
                            </a>
                            <a href="#" class="footer-link">
                                <i class="fab fa-discord"></i>
                            </a>
                        </div>
                    </div>
                    <div class="text-center mt-4 text-muted small">
                        © 2025 ExpenseTracker. Helping you achieve financial freedom.
                    </div>
                </div>
            </footer>
            
        </div>

        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>