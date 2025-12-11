@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-900">Expense Details</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('expenses.edit', $expense->id) }}" 
                       class="inline-flex items-center px-3 py-1 border border-gray-300 rounded text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Edit
                    </a>
                    <a href="{{ route('expenses.index') }}" 
                       class="inline-flex items-center px-3 py-1 border border-gray-300 rounded text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Back to List
                    </a>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                        <div class="mt-1">
                            <p class="text-2xl font-bold text-red-600">-${{ number_format($expense->amount, 2) }}</p>
                        </div>
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium 
                                @if($expense->category == 'Food') bg-red-100 text-red-800
                                @elseif($expense->category == 'Transport') bg-blue-100 text-blue-800
                                @elseif($expense->category == 'Shopping') bg-purple-100 text-purple-800
                                @elseif($expense->category == 'Entertainment') bg-pink-100 text-pink-800
                                @elseif($expense->category == 'Bills') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $expense->category }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <div class="mt-1">
                            <p class="text-gray-900">{{ $expense->date->format('F d, Y') }}</p>
                        </div>
                    </div>
                    
                    <!-- Created At -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created</label>
                        <div class="mt-1">
                            <p class="text-gray-900">{{ $expense->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <div class="mt-1">
                            @if($expense->description)
                            <p class="text-gray-900">{{ $expense->description }}</p>
                            @else
                            <p class="text-gray-500 italic">No description provided</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Delete Button -->
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this expense?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-medium text-white bg-red-600 hover:bg-red-700">
                            Delete Expense
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection