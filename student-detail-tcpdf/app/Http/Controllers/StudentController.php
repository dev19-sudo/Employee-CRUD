<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage; 


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('students.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rollno' => 'required',
            'name' => 'required',
            'marks' => 'required',
            'dob'    => 'nullable|date',
            'photo'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        // handle upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('students', 'public');
            // stores in storage/app/public/students/...
        }

        $student = Student::create($validated);

        //return redirect()->route('students.index');

        // return JSON (for AJAX)
        return response()->json([
            'message' => 'Student saved',
            'student' => $student
        ]); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return response()->json($student);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'rollno' => 'required',
            'name' => 'required',
            'marks' => 'required',
            'dob'    => 'nullable|date',
            'photo'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);
        $student = Student::findOrFail($id);

        // if new photo uploaded -> delete old + store new + update photo path
        if ($request->hasFile('photo')) {
            // delete old photo (optional but good)
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = $request->file('photo')->store('students', 'public');
        }

        // update all fields (including photo path if set)
        $student->update($validated);

        // return JSON (for AJAX)
        return response()->json([
            'message' => 'Student saved',
            'student' => $student
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        if ($student->photo && Storage::disk('public')->exists($student->photo)) {
            Storage::disk('public')->delete($student->photo);
        }
        $student->delete();
        //return redirect()->route('students.index');
        return response()->json(['message' => 'Deleted']);
    }

    public function data()
    {
        return DataTables::eloquent(Student::query())
            ->addColumn('photo_link', function ($student) {
                if (!$student->photo) return '-';

                $url = Storage::url($student->photo); 

                return '<img src="'.$url.'" width="60" height="60" style="object-fit:cover;border-radius:6px;">';
            })
            ->addColumn('action', function ($student) {
                return '
                    <button type="button" class="btn btn-sm btn-warning editStudent" data-id="'.$student->id.'">
                        Edit
                    </button>
                    <button type="button" class="btn btn-sm btn-danger deleteStudent" data-id="'.$student->id.'">
                        Delete
                    </button>
                ';
            })
            ->rawColumns(['photo_link', 'action'])
            ->toJson();
    }
}
