@extends('layouts.app')

@section('content')

<table id="studentsTable" class="table table-bordered text-center">
    <thead>
        <tr>
            <th>Roll No.</th>
            <th>Name</th>
            <th>Marks</th>
        </tr>
    </thead>
</table>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
  $('#studentsTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('students.data') }}",
    pageLength: 10,
    columns: [
      { data: 'rollno', name: 'rollno' },
      { data: 'name',   name: 'name' },
      { data: 'marks',  name: 'marks' }
    ]
  });
});
</script>
@endpush
