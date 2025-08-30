@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Payment Statistics</h1>
            <p class="text-gray-600 mt-2">Overview of payment transactions and performance metrics</p>
        </div>

        @if(empty($paymentStatistics))
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Payment Data Available</h3>
                    <p class="text-gray-500">There are currently no payment statistics to display. Data will appear here once payments are processed.</p>
                </div>
            </div>
        @else
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Payments -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Amount</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ isset($statistics['total_amount']) ? number_format($statistics['total_amount'], 2) : '0.00' }}
                                <span class="text-sm text-gray-500">{{ $statistics['primary_currency'] ?? 'USD' }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Transactions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Payments</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $statistics['total_count'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Success Rate -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Success Rate</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($statistics['success_rate'] ?? 0, 1) }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Average Amount -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Average Amount</p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ isset($statistics['average_amount']) ? number_format($statistics['average_amount'], 2) : '0.00' }}
                                <span class="text-sm text-gray-500">{{ $statistics['primary_currency'] ?? 'USD' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Status Distribution -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Status Distribution</h3>
                    <div class="space-y-4">
                        @if(isset($statistics['status_breakdown']))
                            @foreach($statistics['status_breakdown'] as $status => $data)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-3 
                                            @if($status === 'completed') bg-green-500
                                            @elseif($status === 'pending') bg-yellow-500
                                            @elseif($status === 'failed') bg-red-500
                                            @elseif($status === 'refunded') bg-gray-500
                                            @endif">
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $status }}</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-900">{{ $data['count'] }}</div>
                                        <div class="text-xs text-gray-500">{{ number_format($data['percentage'], 1) }}%</div>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full 
                                        @if($status === 'completed') bg-green-500
                                        @elseif($status === 'pending') bg-yellow-500
                                        @elseif($status === 'failed') bg-red-500
                                        @elseif($status === 'refunded') bg-gray-500
                                        @endif" 
                                        style="width: {{ $data['percentage'] }}%">
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Currency Breakdown -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Currency Distribution</h3>
                    <div class="space-y-3">
                        @if(isset($statistics['currency_breakdown']))
                            @foreach($statistics['currency_breakdown'] as $currency => $data)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="text-sm font-bold text-gray-900 bg-white px-2 py-1 rounded border">{{ $currency }}</span>
                                        <span class="ml-3 text-sm text-gray-600">{{ $data['count'] }} transactions</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-900">{{ number_format($data['total_amount'], 2) }}</div>
                                        <div class="text-xs text-gray-500">{{ number_format($data['percentage'], 1) }}%</div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Payments Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Payments</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($paymentStatistics as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">#{{ $payment->payment_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">{{ number_format($payment->amount, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ strtoupper($payment->currency) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($payment->status === 'completed') bg-green-100 text-green-800
                                            @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($payment->status === 'failed') bg-red-100 text-red-800
                                            @elseif($payment->status === 'refunded') bg-gray-100 text-gray-800
                                            @endif">
                                            @if($payment->status === 'completed')
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            @elseif($payment->status === 'pending')
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                </svg>
                                            @elseif($payment->status === 'failed')
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            @elseif($payment->status === 'refunded')
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->created_at->format('M d, Y') }}
                                        <div class="text-xs text-gray-400">{{ $payment->created_at->format('H:i') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        No payment records found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination if needed -->
                @if(method_exists($paymentStatistics, 'links'))
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $paymentStatistics->links() }}
                    </div>
                @endif
            </div>

            <!-- Additional Insights -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Monthly Trends -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Performance</h3>
                    @if(isset($statistics['monthly_data']))
                        <div class="space-y-3">
                            @foreach($statistics['monthly_data'] as $month => $data)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">{{ $month }}</span>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-900">{{ number_format($data['amount'], 2) }}</div>
                                        <div class="text-xs text-gray-500">{{ $data['count'] }} payments</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Monthly trend data will appear here as more payments are processed.</p>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('payments.export') ?? '#' }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium transition-colors duration-200">
                            Export Data
                        </a>
                        <a href="{{ route('payments.failed') ?? '#' }}" class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium transition-colors duration-200">
                            View Failed Payments
                        </a>
                        <a href="{{ route('payments.refunds') ?? '#' }}" class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium transition-colors duration-200">
                            Manage Refunds
                        </a>
                        <a href="{{ route('payments.create') ?? '#' }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg text-sm font-medium transition-colors duration-200">
                            New Payment
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filter and Search Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters & Search</h3>
                <form method="GET" action="{{ request()->url() }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                    <div>
                        <label for="currency_filter" class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                        <select name="currency" id="currency_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Currencies</option>
                            @if(isset($statistics['available_currencies']))
                                @foreach($statistics['available_currencies'] as $currency)
                                    <option value="{{ $currency }}" {{ request('currency') === $currency ? 'selected' : '' }}>{{ strtoupper($currency) }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors duration-200">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection