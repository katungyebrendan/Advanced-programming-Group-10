<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OutcomeController extends Controller
{
    public function index($projectId)
    {
        // show all outcomes for a given project
        return view('outcomes.index', compact('projectId'));
    }

    public function create($projectId)
    {
        // form to create an outcome for a given project
        return view('outcomes.create', compact('projectId'));
    }

    public function store(Request $request, $projectId)
    {
        // normally save outcome here
        // then redirect back to outcomes list of that project
        return redirect()->route('projects.outcomes.index', $projectId);
    }

    public function show($id)
    {
        // show a single outcome
        return view('outcomes.show', compact('id'));
    }

    public function edit($id)
    {
        // form to edit an outcome
        return view('outcomes.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // normally update outcome here
        // then redirect back to show page
        return redirect()->route('outcomes.show', $id);
    }

    public function destroy($id)
    {
        // normally delete outcome here
        // then redirect back to project’s outcomes list
        return redirect()->route('projects.index'); 
    }
}
