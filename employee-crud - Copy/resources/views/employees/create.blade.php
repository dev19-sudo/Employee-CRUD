@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h3 class="mb-0">Create Employee</h3>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('employees.index') }}" class="btn btn-outline-dark">Back</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST"
              action="{{ route('employees.store') }}"
              enctype="multipart/form-data">
            @csrf

            @include('employees._form', [
                'employee' => null,
                'departments' => $departments,
                'skillsList' => $skillsList
            ])

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
