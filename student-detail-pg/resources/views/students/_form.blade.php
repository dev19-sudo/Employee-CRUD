
<div class="row g-3">
    {{-- Student Roll No. --}}
    <div class="col-md-4">
        <label class="form-label">Student Roll No. <span class="text-danger">*</span></label>
        <input type="text"
               name="rollno"
               value=""
               class="form-control @error('rollno') is-invalid @enderror">
        @error('rollno') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Name --}}
    <div class="col-md-4">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text"
               name="name"
               value=""
               class="form-control @error('name') is-invalid @enderror">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Marks --}}
    <div class="col-md-4">
        <label class="form-label">Marks <span class="text-danger">*</span></label>
        <input type="text"
               name="marks"
               value=""
               class="form-control @error('marks') is-invalid @enderror">
        @error('marks') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
