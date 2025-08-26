@extends('layouts.auth')

@section('title', 'Welcome')
@section('subtitle', 'Hostel Management System')

@section('content')
<div class="text-center">
    <div class="mb-4">
        <i class="fas fa-building fa-4x text-primary"></i>
                </div>

    <h2 class="mb-4">Welcome</h2>
    
    <div class="d-grid gap-2">
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-gauge"></i> Go to Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-user-plus"></i> Register
            </a>
        @endauth
                        </div>
                    </div>
@endsection
