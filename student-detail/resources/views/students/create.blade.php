@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h3 class="mb-0">Create Student</h3>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('students.index') }}" class="btn btn-outline-dark">Back</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST"
              action="{{ route('students.store') }}">
            @csrf

            @include('students._form', [
            ])

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

