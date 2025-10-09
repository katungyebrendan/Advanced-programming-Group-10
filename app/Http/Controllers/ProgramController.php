<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use App\Http\Requests\CreateProgramRequest;
use App\Http\Requests\UpdateProgramRequest;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::orderBy('name')->paginate(12);
        return view('programs.index', compact('programs'));
    }

    public function create()
    {
        $focusOptions = ['IoT', 'Automation', 'Renewables', 'Software', 'Business'];
        $phaseOptions = ['Cross-Skilling', 'Collaboration', 'Technical Skills', 'Prototyping', 'Commercialization'];
        
        return view('programs.create', compact('focusOptions', 'phaseOptions'));
    }

    public function store(CreateProgramRequest $request)
    {
        try {
            $data = $request->validated();

            // Ensure arrays are properly handled
            $data['focus_areas'] = $data['focus_areas'] ?? [];
            $data['phases'] = $data['phases'] ?? [];

            $program = Program::create($data);

            return redirect()->route('programs.index')
                           ->with('success', 'Program created successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the program: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Program $program)
    {
        return view('programs.show', compact('program'));
    }

    public function edit(Program $program)
    {
        $focusOptions = ['IoT', 'Automation', 'Renewables', 'Software', 'Business'];
        $phaseOptions = ['Cross-Skilling', 'Collaboration', 'Technical Skills', 'Prototyping', 'Commercialization'];
        
        return view('programs.edit', compact('program', 'focusOptions', 'phaseOptions'));
    }

    public function update(UpdateProgramRequest $request, Program $program)
    {
        try {
            $data = $request->validated();

            // Ensure arrays are properly handled
            $data['focus_areas'] = $data['focus_areas'] ?? [];
            $data['phases'] = $data['phases'] ?? [];

            $program->update($data);

            return redirect()->route('programs.index')
                           ->with('success', 'Program updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the program: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Program $program)
    {
        try {
            if (!$program->canBeDeleted()) {
                return back()->with('error', $program->getDeletionBlockReason());
            }

            $program->delete();
            return redirect()->route('programs.index')
                           ->with('success', 'Program deleted successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the program: ' . $e->getMessage());
        }
    }
}