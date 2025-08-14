@extends('layouts.app')

@section('title', 'Students - Hostel Management System')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-users"></i> Students</h1>
    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Student
    </a>
</div>

@if($students->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Joined Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td>
                                    <strong>{{ $student->name }}</strong>
                                </td>
                                <td>
                                    <i class="fas fa-envelope"></i> {{ $student->email }}
                                </td>
                                <td>
                                    <i class="fas fa-phone"></i> {{ $student->phone }}
                                </td>
                                <td>{{ Str::limit($student->address, 40) }}</td>
                                <td>
                                    <small class="text-muted">
                                        {{ $student->created_at->format('M d, Y') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('students.show', $student->id) }}" 
                                           class="btn btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('students.edit', $student->id) }}" 
                                           class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('students.destroy', $student->id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this student?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <p class="text-muted">Total Students: <strong>{{ $students->count() }}</strong></p>
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h4>No Students Found</h4>
            <p class="text-muted">There are no students registered yet.</p>
            <a href="{{ route('students.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add First Student
            </a>
        </div>
    </div>
@endif
@endsection