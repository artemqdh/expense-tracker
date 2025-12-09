<!DOCTYPE html>
<html>
<head>
    <title>Add Expense</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Add New Expense</h1>
        
        <form method="POST" action="{{ route('expenses.store') }}">
            @csrf
            
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                @error('amount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
                @error('category')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
                @error('date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary">Save Expense</button>
            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>