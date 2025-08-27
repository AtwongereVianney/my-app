@extends('layouts.app') {{-- or whatever your main layout is --}}

@section('content')
<div class="container">
    <h1>Payment Statistics</h1>
    
    <div class="row">
        <div class="col-md-12">
            {{-- Your payment statistics content here --}}
            @if(empty($paymentStatistics))
                <p>No payment statistics available.</p>
            @else
                {{-- Display your statistics --}}
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Statistics Overview</h5>
                        {{-- Add your statistics display logic here --}}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection