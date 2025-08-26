@extends('layouts.app')

@section('title', 'Payment Statistics')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-icon"></i> ModelNames</h1>
    <a href="{{ route('modelnames.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New
    </a>
</div>

@if($modelnames->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Field 1</th>
                            <th>Field 2</th>
                            <!-- Add more columns as needed -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($modelnames as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->field1 }}</td>
                                <td>{{ $item->field2 }}</td>
                                <!-- Add more fields as needed -->
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('modelnames.show', $item->id) }}" 
                                           class="btn btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('modelnames.edit', $item->id) }}" 
                                           class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('modelnames.destroy', $item->id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
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
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-icon fa-3x text-muted mb-3"></i>
            <h4>No Records Found</h4>
            <a href="{{ route('modelnames.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add First Record
            </a>
        </div>
    </div>
@endif
@endsection