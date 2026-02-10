@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h3 class="mb-0">Employee Listing</h3>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('employees.create') }}" class="btn btn-primary">+ Create Employee</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('employees.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text"
                       name="q"
                       value="{{ $q ?? request('q') }}"
                       class="form-control"
                       placeholder="Search by name or email">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" type="submit">Search</button>
            </div>
            <div class="col-md-2">
                <a class="btn btn-outline-dark w-100" href="{{ route('employees.index') }}">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Sort</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($employees as $index => $emp)
                    <tr>
                        <td>{{ $employees->firstItem() + $index }}</td>

                        <td style="width:70px;">
                            @if($emp->photo)
                                <img src="{{ asset('storage/'.$emp->photo) }}"
                                     alt="photo"
                                     class="rounded"
                                     style="width:50px;height:50px;object-fit:cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                     style="width:50px;height:50px;">
                                    <span class="text-white small">N/A</span>
                                </div>
                            @endif
                        </td>

                        <td>{{ $emp->employee_code }}</td>
                        <td>{{ $emp->name }}</td>
                        <td>{{ $emp->email ?? '-' }}</td>
                        <td>{{ $emp->mobile }}</td>
                        <td>{{ $emp->department?->name ?? '-' }}</td>

                        <td>
                            <span class="badge {{ $emp->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $emp->status ? 'Active' : 'Inactive' }}
                            </span>

                            <form method="POST"
                                  action="{{ route('employees.toggle-status', $emp->id) }}"
                                  class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-primary ms-2">
                                    Toggle
                                </button>
                            </form>
                        </td>

                        <td>{{ $emp->sort_order }}</td>

                        <td class="text-end">
                            <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('employees.destroy', $emp->id) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this employee?');">
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
                        <td colspan="10" class="text-center text-muted py-4">
                            No employees found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $employees->links() }}
        </div>
    </div>
</div>
@endsection
