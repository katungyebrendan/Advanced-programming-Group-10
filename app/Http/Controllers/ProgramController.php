<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::orderBy('name')->paginate(12);
        return view('programs.index', compact('programs'));
    }

    public function create()
    {
        $focusOptions = ['IoT','Automation','Renewables','Software','Business']; // customize
        $phaseOptions = ['Cross-Skilling','Collaboration','Technical Skills','Prototyping','Commercialization'];
        return view('programs.create', compact('focusOptions','phaseOptions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'national_alignment' => 'nullable|string|max:255',
            'focus_areas' => 'nullable|array',
            'focus_areas.*' => 'string|max:150',
            'phases' => 'nullable|array',
            'phases.*' => 'string|max:100',
        ]);

        Program::create($data);

        return redirect()->route('programs.index')->with('success', 'Program created.');
    }

    public function show(Program $program)
    {
        return view('programs.show', compact('program'));
    }

    public function edit(Program $program)
    {
        $focusOptions = ['IoT','Automation','Renewables','Software','Business'];
        $phaseOptions = ['Cross-Skilling','Collaboration','Technical Skills','Prototyping','Commercialization'];
        return view('programs.edit', compact('program','focusOptions','phaseOptions'));
    }

    public function update(Request $request, Program $program)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'national_alignment' => 'nullable|string|max:255',
            'focus_areas' => 'nullable|array',
            'focus_areas.*' => 'string|max:150',
            'phases' => 'nullable|array',
            'phases.*' => 'string|max:100',
        ]);

        $program->update($data);

        return redirect()->route('programs.index')->with('success', 'Program updated.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('programs.index')->with('success', 'Program deleted.');
    }
}
