@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h3 class="mb-0">Departments</h3>
        <small class="text-muted">Manage department list for employee dropdown.</small>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('departments.create') }}" class="btn btn-primary">+ Create Department</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('departments.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text"
                       name="q"
                       value="{{ $q ?? request('q') }}"
                       class="form-control"
                       placeholder="Search by department name">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" type="submit">Search</button>
            </div>
            <div class="col-md-2">
                <a class="btn btn-outline-dark w-100" href="{{ route('departments.index') }}">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($departments as $index => $dept)
                    <tr>
                        <td>{{ $departments->firstItem() + $index }}</td>
                        <td>{{ $dept->name }}</td>
                        <td class="text-end">
                            <a href="{{ route('departments.edit', $dept->id) }}" class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('departments.destroy', $dept->id) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete this department?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            No departments found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $departments->links() }}
        </div>
    </div>
</div>
@endsection
