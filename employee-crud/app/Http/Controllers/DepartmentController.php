<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $departments = Department::query()
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('departments.index', compact('departments', 'q'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department created.');
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Department updated.');
    }

    public function destroy(Department $department)
    {
        // Optional: block delete if employees exist
        if ($department->employees()->count() > 0) {
            return redirect()->route('departments.index')
                ->with('error', 'Cannot delete department: employees exist.');
        }

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted.');
    }

    public function ajaxList(Request $request)
    {
        $search = $request->get('q');          // Select2 sends term as "q" (we will set it)
        $page   = (int) $request->get('page', 1);
        $perPage = 10;

        $query = \App\Models\Department::query()
            ->select('id', 'name')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name');

        $total = $query->count();

        $items = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(function ($d) {
                return [
                    'id' => $d->id,
                    'text' => $d->name, // Select2 expects "text"
                ];
            });

        return response()->json([
            'results' => $items,
            'pagination' => [
                'more' => ($page * $perPage) < $total
            ],
        ]);
    }
}
