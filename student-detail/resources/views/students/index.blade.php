@extends('layouts.app')

@section('content') 
<div class="w-50 mx-auto my-1">
<table  class="table table-bordered text-center">
    <tr>
        <th>Roll No.</th>
        <th>Name</th>
        <th>Marks</th>
    </tr>
    @foreach ($students as $student)
    <tr>
        <td>{{ $student->rollno }}</td>
        <td>{{ $student->name }}</td>
        <td>{{ $student->marks }}</td>
        <td class="text-align-last-center">
                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-warning">
                    Edit
                </a>

                <form method="POST"
                      action="{{ route('students.destroy', $student->id) }}"
                      class="d-inline"
                      onsubmit="return confirm('Are you sure you want to delete this student?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">
                    Delete
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
</div>
@endsection
