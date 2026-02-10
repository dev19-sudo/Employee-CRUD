<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $employees = Employee::with('department')
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('employees.index', compact('employees', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $skillsList = ['PHP','Laravel','MySQL','HTML','CSS','JS']; // you can change
        return view('employees.create', compact('departments', 'skillsList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_code' => ['required', 'string', 'max:50', 'unique:employees,employee_code'],
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['nullable', 'email', 'max:255'],
            'mobile'        => ['required', 'string', 'max:20'],
            'joining_date'  => ['nullable', 'date'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'gender'        => ['nullable', Rule::in(['male','female','other'])],
            'skills'        => ['nullable', 'array'],
            'skills.*'      => ['string', 'max:50'],
            'address'       => ['nullable', 'string'],
            'photo'         => ['nullable', 'image', 'max:2048'],
            'status'        => ['required', 'boolean'],
            'sort_order'    => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $validated['created_by'] = auth()->id(); // if auth exists, else remove

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Employee created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $departments = Department::orderBy('name')->get();
        $skillsList = ['PHP','Laravel','MySQL','HTML','CSS','JS'];
        return view('employees.edit', compact('employee','departments','skillsList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_code' => [
                'required','string','max:50',
                Rule::unique('employees','employee_code')->ignore($employee->id)
            ],
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['nullable', 'email', 'max:255'],
            'mobile'        => ['required', 'string', 'max:20'],
            'joining_date'  => ['nullable', 'date'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'gender'        => ['nullable', Rule::in(['male','female','other'])],
            'skills'        => ['nullable', 'array'],
            'skills.*'      => ['string', 'max:50'],
            'address'       => ['nullable', 'string'],
            'photo'         => ['nullable', 'image', 'max:2048'],
            'status'        => ['required', 'boolean'],
            'sort_order'    => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted.');
    }

    /**
     * Toggle the status of the specified employee.
     */
    public function toggleStatus(Employee $employee)
    {
        $employee->update(['status' => !$employee->status]);
        return redirect()->route('employees.index')->with('success', 'Status updated.');
    }
}
