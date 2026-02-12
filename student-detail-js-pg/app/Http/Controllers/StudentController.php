<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Yajra\DataTables\Facades\DataTables;

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
        ]);

        $student = Student::create($validated);

        // return JSON (for AJAX)
        return response()->json([
            'message' => 'Student saved',
            'student' => $student
        ]); 
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
        ]);
        $student = Student::findOrFail($id);
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
        $student->delete();
        //return redirect()->route('students.index');
        return response()->json(['message' => 'Deleted']);
    }

    public function data()
    {
        return DataTables::eloquent(Student::query())
            ->addColumn('action', function ($student) {
                $editUrl = route('students.edit', $student->id);

                return '
                <a href="'.$editUrl.'" class="btn btn-sm btn-warning">Edit</a>
                <button type="button"
                        class="btn btn-sm btn-danger deleteStudent"
                        data-id="'.$student->id.'">
                    Delete
                </button>
                ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
