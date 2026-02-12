
<div class="row g-3">
    {{-- Student Roll No. --}}
    <div class="col-md-4">
        <label class="form-label">Student Roll No. <span class="text-danger">*</span></label>
        <input type="text"
               name="rollno"
               value="{{ old('rollno', $student->rollno ?? '') }}"
               class="form-control @error('rollno') is-invalid @enderror">
        @error('rollno') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Name --}}
    <div class="col-md-4">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text"
               name="name"
               value="{{ old('name', $student->name ?? '') }}"
               class="form-control @error('name') is-invalid @enderror">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Marks --}}
    <div class="col-md-4">
        <label class="form-label">Marks <span class="text-danger">*</span></label>
        <input type="text"
               name="marks"
               value="{{ old('marks', $student->marks ?? '') }}"
               class="form-control @error('marks') is-invalid @enderror">
        @error('marks') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- DOB --}}
    <div class="mb-3">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="dob" class="form-control" value="{{ old('dob', $student->dob ?? '') }}">
    </div>

    {{-- Photo --}}
    <div class="mb-3">
        <label class="form-label">Photo</label>
        <input type="file" name="photo" class="form-control">
    </div>
</div>
