<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index()
    {
        return view('participants.index');
    }

    public function create()
    {
        return view('participants.create');
    }

    public function store(Request $request)
    {
        return view('participants.store');
    }

    public function show($id)
    {
        return view('participants.show', compact('id'));
    }

    public function edit($id)
    {
        return view('participants.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return view('participants.update', compact('id'));
    }

    public function destroy($id)
    {
        return view('participants.destroy', compact('id'));
    }

    public function projects($participantId)
    {
        return view('participants.projects', compact('participantId'));
    }
}