@extends('layouts.app')

@section('content')
<div class="container mt-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Students</h3>

        {{-- Export/Download Dropdown --}}
        <div class="d-flex gap-2">
          <div class="dropdown">
            <button class="btn btn-success btn-sm dropdown-toggle" style="height: -webkit-fill-available; border-radius:8px; " type="button"
                    id="downloadMenuBtn" data-bs-toggle="dropdown" aria-expanded="false">
              Download
            </button>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="downloadMenuBtn">
              <li><a class="dropdown-item" href="#" id="downloadExcel">Download Excel</a></li>
              <li><a class="dropdown-item" href="{{ route('report.download') }}" id="downloadPdf">Download PDF</a></li>
            </ul>
          </div>

          {{-- Open Create Modal --}}
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
              Create Student
          </button>
        </div>
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

<!-- CREATE MODAL -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Create Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div id="createErrors"></div>

        <form id="createStudentForm" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Roll No <span class="text-danger">*</span></label>
              <input type="text" name="rollno" class="form-control">
            </div>

            <div class="col-md-4">
              <label class="form-label">Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control">
            </div>

            <div class="col-md-4">
              <label class="form-label">Marks <span class="text-danger">*</span></label>
              <input type="text" name="marks" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">DOB</label>
              <input type="date" name="dob" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">Photo</label>
              <input type="file" name="photo" class="form-control">
            </div>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="saveStudentBtn" class="btn btn-primary">Save</button>
      </div>

    </div>
  </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Edit Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div id="editErrors"></div>

        <form id="editStudentForm" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" id="edit_id">

          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Roll No *</label>
              <input type="text" name="rollno" id="edit_rollno" class="form-control">
            </div>

            <div class="col-md-4">
              <label class="form-label">Name *</label>
              <input type="text" name="name" id="edit_name" class="form-control">
            </div>

            <div class="col-md-4">
              <label class="form-label">Marks *</label>
              <input type="text" name="marks" id="edit_marks" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">DOB</label>
              <input type="date" name="dob" id="edit_dob" class="form-control">
            </div>

            <div class="col-md-6">
              <label class="form-label">Photo (optional)</label>
              <input type="file" name="photo" class="form-control">
              <div class="mt-2" id="currentPhoto"></div>
            </div>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="updateStudentBtn" class="btn btn-primary">Update</button>
      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  function showMsg(text){
    $('#msg').html('<div class="alert alert-success">'+text+'</div>').hide().fadeIn(200);
    setTimeout(function () {
      $('#msg').fadeOut(400, function(){ $(this).html('').show(); });
    }, 3000);
  }

  function showErrors(target, xhr){
    let errors = xhr.responseJSON?.errors;
    let html = '<div class="alert alert-danger"><ul>';
    if(errors){
      Object.values(errors).forEach(arr => html += `<li>${arr[0]}</li>`);
    } else {
      html += '<li>Something went wrong</li>';
    }
    html += '</ul></div>';
    $(target).html(html);
  }

  // DataTable
  let table = $('#studentsTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('students.data') }}",
    pageLength: 10,
    lengthMenu: [5, 10, 25, 50, 100],

    dom: 'lBfrtip',
    buttons: [
      {
        extend: 'excelHtml5',
        title: 'Students',
        exportOptions: { columns: [0,1,2,3] } // rollno,name,marks,dob
      }
    ],

    columns: [
      { data: 'rollno', name: 'rollno' },
      { data: 'name',   name: 'name' },
      { data: 'marks',  name: 'marks' },
      { data: 'dob',    name: 'dob' },
      { data: 'photo_link', name: 'photo', orderable:false, searchable:false },
      { data: 'action', name: 'action', orderable:false, searchable:false }
    ]
  });

  // optional: hide default buttons row
  table.buttons().container().hide();

  $('#downloadExcel').on('click', function(e){
    e.preventDefault();
    table.button('.buttons-excel').trigger();
  });


  // Trigger export based on dropdown selection
  $('#exportBtn').on('click', function () {
    let type = $('#exportType').val();

    if (type === 'excel') {
      table.button('.buttons-excel').trigger();
    } 
  });

  // CREATE
  $('#saveStudentBtn').on('click', function () {
    $('#createErrors').html('');
    let form = $('#createStudentForm')[0];
    let formData = new FormData(form);

    $.ajax({
      url: "{{ route('students.store') }}",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function () {
        bootstrap.Modal.getInstance(document.getElementById('createModal')).hide();
        form.reset();
        showMsg('Saved!');
        table.ajax.reload(null, false);
      },
      error: function (xhr) {
        showErrors('#createErrors', xhr);
      }
    });
  });

  // OPEN EDIT MODAL
  $(document).on('click', '.editStudent', function () {
    $('#editErrors').html('');
    let id = $(this).data('id');

    $.get("/students/" + id, function (student) {
      $('#edit_id').val(student.id);
      $('#edit_rollno').val(student.rollno ?? '');
      $('#edit_name').val(student.name ?? '');
      $('#edit_marks').val(student.marks ?? '');
      $('#edit_dob').val(student.dob ?? '');

      new bootstrap.Modal(document.getElementById('editModal')).show();
    });
  });

  // UPDATE
  $('#updateStudentBtn').on('click', function () {
    $('#editErrors').html('');
    let id = $('#edit_id').val();

    let form = $('#editStudentForm')[0];
    let formData = new FormData(form);

    $.ajax({
      url: "/students/" + id,
      type: "POST",
      data: formData, // includes _method=PUT
      processData: false,
      contentType: false,
      success: function () {
        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
        showMsg('Updated!');
        table.ajax.reload(null, false);
        form.reset();
        $('#currentPhoto').html('');
      },
      error: function (xhr) {
        showErrors('#editErrors', xhr);
      }
    });
  });

  // DELETE
  $(document).on('click', '.deleteStudent', function () {
    if(!confirm('Are you sure you want to delete this student?')) return;

    let id = $(this).data('id');

    $.ajax({
      url: "/students/" + id,
      type: "POST",
      data: { _method: "DELETE" },
      success: function () {
        showMsg('Deleted!');
        table.ajax.reload(null, false);
      },
      error: function () {
        alert('Delete failed');
      }
    });
  });

});
</script>
@endpush
