@extends('layouts.app')

@section('content')
<div class="row mb-3 my-4 mx-5">
    <div class="col-md-6">
        <h3 class="mb-0">Create Student</h3>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('students.index') }}" class="btn btn-outline-dark">Back</a>
    </div>
</div>

<div class="card container mt-3">
    <div class="card-body">
        <form id="studentForm" method="POST" enctype="multipart/form-data">
            @csrf

            @include('students._form')

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>

        <div id="msg" class="mt-3"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  $('#studentForm').on('submit', function(e){
    e.preventDefault();

    let formData = new FormData(this); // ✅ includes file + all inputs

    $.ajax({
      url: "{{ route('students.store') }}",
      type: "POST",
      data: formData,
      processData: false, // ✅ required for FormData
      contentType: false, // ✅ required for FormData
      success: function(res){
        $('#msg')
          .html('<div class="alert alert-success">Saved!</div>')
          .hide()
          .fadeIn(200);

        $('#studentForm')[0].reset();

        setTimeout(function () {
          $('#msg').fadeOut(400, function () {
            $(this).html('').show();
          });
        }, 3000);
      },
      error: function(xhr){
        let errors = xhr.responseJSON?.errors;
        let html = '<div class="alert alert-danger"><ul>';

        if(errors){
          Object.values(errors).forEach(arr => html += `<li>${arr[0]}</li>`);
        } else {
          html += '<li>Something went wrong</li>';
        }

        html += '</ul></div>';
        $('#msg').html(html);
      }
    });

  });

});
</script>
@endpush
