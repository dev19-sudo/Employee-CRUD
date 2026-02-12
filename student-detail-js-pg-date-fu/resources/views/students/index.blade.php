@extends('layouts.app')

@section('content')
<div class="container mt-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Students</h3>
        <a href="{{ route('students.create') }}" class="btn btn-primary">Create Student</a>
    </div>

    <div id="msg"></div>

    <table id="studentsTable" class="table table-bordered text-center align-middle">
        <thead>
            <tr>
                <th>Roll No.</th>
                <th>Name</th>
                <th>Marks</th>
                <th>DOB</th>
                <th>Photo</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

  // CSRF for all ajax requests
  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  // DataTable with pagination
  let table = $('#studentsTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('students.data') }}",
    pageLength: 10,
    lengthMenu: [5, 10, 25, 50, 100],
    columns: [
      { data: 'rollno', name: 'rollno' },
      { data: 'name',   name: 'name' },
      { data: 'marks',  name: 'marks' },
      { data: 'dob',    name: 'dob' },
      { data: 'photo_link', name: 'photo', orderable: false, searchable: false },
      { data: 'action', name: 'action', orderable: false, searchable: false }
    ]
  });

  // Delete using jQuery (AJAX)
  $(document).on('click', '.deleteStudent', function () {
    if(!confirm('Are you sure you want to delete this student?')) return;

    let id = $(this).data('id');

    $.ajax({
      url: "/students/" + id,
      type: "POST",
      data: { _method: "DELETE" },
      success: function () {

        $('#msg')
          .html('<div class="alert alert-success">Deleted!</div>')
          .hide()
          .fadeIn(200);

        // reload table data after delete
        table.ajax.reload(null, false);

        // hide after 3 seconds
        setTimeout(function () {
          $('#msg').fadeOut(400, function () {
            $(this).html('').show();
          });
        }, 3000);
      },
      error: function () {
        alert('Delete failed');
      }
    });
  });

});
</script>
@endpush
