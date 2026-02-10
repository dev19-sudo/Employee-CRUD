@php
    // For edit: $employee exists; for create: null
    $selectedSkills = old('skills', $employee->skills ?? []);
@endphp

<div class="row g-3">
    {{-- Employee Code --}}
    <div class="col-md-4">
        <label class="form-label">Employee Code <span class="text-danger">*</span></label>
        <input type="text"
               name="employee_code"
               value="{{ old('employee_code', $employee->employee_code ?? '') }}"
               class="form-control @error('employee_code') is-invalid @enderror">
        @error('employee_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Name --}}
    <div class="col-md-4">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text"
               name="name"
               value="{{ old('name', $employee->name ?? '') }}"
               class="form-control @error('name') is-invalid @enderror">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Mobile --}}
    <div class="col-md-4">
        <label class="form-label">Mobile <span class="text-danger">*</span></label>
        <input type="text"
               name="mobile"
               value="{{ old('mobile', $employee->mobile ?? '') }}"
               class="form-control @error('mobile') is-invalid @enderror">
        @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Email --}}
    <div class="col-md-4">
        <label class="form-label">Email</label>
        <input type="email"
               name="email"
               value="{{ old('email', $employee->email ?? '') }}"
               class="form-control @error('email') is-invalid @enderror">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Joining Date --}}
    <div class="col-md-4">
        <label class="form-label">Joining Date</label>
        <input type="date"
               name="joining_date"
               value="{{ old('joining_date', isset($employee->joining_date) ? $employee->joining_date->format('Y-m-d') : '') }}"
               class="form-control @error('joining_date') is-invalid @enderror">
        @error('joining_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Department --}}
    <div class="col-md-4">
        <label class="form-label">Department</label>
        <select name="department_id"
                class="form-select @error('department_id') is-invalid @enderror">
            <option value="">-- Select Department --</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}"
                    @selected(old('department_id', $employee->department_id ?? '') == $dept->id)>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>
        @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Gender --}}
    <div class="col-md-6">
        <label class="form-label d-block">Gender</label>

        @php $gender = old('gender', $employee->gender ?? ''); @endphp

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" value="male" id="g_m"
                   @checked($gender === 'male')>
            <label class="form-check-label" for="g_m">Male</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" value="female" id="g_f"
                   @checked($gender === 'female')>
            <label class="form-check-label" for="g_f">Female</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" value="other" id="g_o"
                   @checked($gender === 'other')>
            <label class="form-check-label" for="g_o">Other</label>
        </div>

        @error('gender') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    {{-- Status --}}
    <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            @php $status = old('status', isset($employee) ? (int)$employee->status : 1); @endphp
            <option value="1" @selected($status === 1)>Active</option>
            <option value="0" @selected($status === 0)>Inactive</option>
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Sort Order --}}
    <div class="col-md-3">
        <label class="form-label">Sort Order</label>
        <input type="number"
               name="sort_order"
               value="{{ old('sort_order', $employee->sort_order ?? 0) }}"
               class="form-control @error('sort_order') is-invalid @enderror">
        @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Skills --}}
    <div class="col-md-12">
        <label class="form-label d-block">Skills</label>
        <div class="row">
            @foreach($skillsList as $skill)
                <div class="col-md-2 col-6">
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               name="skills[]"
                               value="{{ $skill }}"
                               id="skill_{{ $loop->index }}"
                               @checked(in_array($skill, $selectedSkills ?? []))>
                        <label class="form-check-label" for="skill_{{ $loop->index }}">
                            {{ $skill }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
        @error('skills') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    {{-- Address --}}
    <div class="col-md-12">
        <label class="form-label">Address</label>
        <textarea name="address"
                  rows="3"
                  class="form-control @error('address') is-invalid @enderror">{{ old('address', $employee->address ?? '') }}</textarea>
        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Photo --}}
    <div class="col-md-6">
        <label class="form-label">Photo</label>
        <input type="file"
               name="photo"
               class="form-control @error('photo') is-invalid @enderror">
        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror

        @if(!empty($employee?->photo))
            <div class="mt-2">
                <small class="text-muted d-block mb-1">Current Photo:</small>
                <img src="{{ asset('storage/'.$employee->photo) }}"
                     alt="current photo"
                     class="rounded"
                     style="width:80px;height:80px;object-fit:cover;">
            </div>
        @endif
    </div>
</div>
