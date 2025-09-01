<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index() {
        $services = Services::all();
        return view('services.index', compact('services'));
    }

    public function create() {
        return view('services.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required',
        ]);
        Services::create($data);
        return redirect()->route('services.index')->with('success','Services created.');
    }

    public function show(Services $service) {
        return view('services.show', ['item' => $service]);
    }

    public function edit(Services $service) {
        return view('services.edit', ['item' => $service]);
    }

    public function update(Request $request, Services $service) {
        $data = $request->validate([
            'name' => 'required',
        ]);
        $service->update($data);
        return redirect()->route('services.index')->with('success','Services updated.');
    }

    public function destroy(Services $service) {
        $service->delete();
        return redirect()->route('services.index')->with('success','Services deleted.');
    }
}
