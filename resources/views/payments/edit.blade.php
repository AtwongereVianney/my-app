@extends('layouts.app')

@section('title', 'Edit Payment - Hostel Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-edit"></i> Edit Payment #{{ $payment->id }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('payments.show', $payment) }}" class="btn btn-info">
            <i class="fas fa-eye"></i> View
        </a>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Payments
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="fas fa-edit"></i> Update Payment Information
        </h3>
    </div>
    
    <div class="card-body">
        <form action="{{ route('payments.update', $payment) }}" method="POST">
            @csrf
            @method('PUT')
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <!-- Booking Selection -->
                <div class="col-md-6 mb-3">
                    <label for="booking_id" class="form-label">
                        Select Booking <span class="text-danger">*</span>
                    </label>
                    <select name="booking_id" id="booking_id" 
                            class="form-select @error('booking_id') is-invalid @enderror">
                        <option value="">Choose a booking...</option>
                        @foreach($bookings as $booking)
                            <option value="{{ $booking->id }}" 
                                {{ (old('booking_id', $payment->booking_id) == $booking->id) ? 'selected' : '' }}>
                                #{{ $booking->id }} - {{ $booking->student->name ?? 'N/A' }} 
                                (Room {{ $booking->room->room_number ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('booking_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Amount -->
                <div class="col-md-6 mb-3">
                    <label for="amount" class="form-label">
                        Amount <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="amount" id="amount" 
                               value="{{ old('amount', $payment->amount) }}" 
                               step="0.01" min="0.01"
                               class="form-control @error('amount') is-invalid @enderror"
                               placeholder="0.00">
                    </div>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <!-- Payment Date -->
                <div class="col-md-6 mb-3">
                    <label for="payment_date" class="form-label">
                        Payment Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="payment_date" id="payment_date" 
                           value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}"
                           class="form-control @error('payment_date') is-invalid @enderror">
                    @error('payment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div class="col-md-6 mb-3">
                    <label for="payment_method" class="form-label">
                        Payment Method <span class="text-danger">*</span>
                    </label>
                    <select name="payment_method" id="payment_method" 
                            class="form-select @error('payment_method') is-invalid @enderror">
                        <option value="">Choose payment method...</option>
                        @foreach(App\Models\Payment::getPaymentMethodLabels() as $value => $label)
                            <option value="{{ $value }}" 
                                {{ (old('payment_method', $payment->payment_method) == $value) ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        @foreach(App\Models\Payment::getStatusLabels() as $value => $label)
                            <option value="{{ $value }}" 
                                {{ (old('status', $payment->status) == $value) ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Reference Number -->
                <div class="col-md-6 mb-3">
                    <label for="reference_number" class="form-label">Reference Number</label>
                    <input type="text" name="reference_number" id="reference_number" 
                           value="{{ old('reference_number', $payment->reference_number) }}"
                           class="form-control @error('reference_number') is-invalid @enderror"
                           placeholder="Transaction ID, Receipt #, etc.">
                    @error('reference_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="form-control @error('notes') is-invalid @enderror"
                              placeholder="Additional notes about this payment...">{{ old('notes', $payment->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('payments.show', $payment) }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Payment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
