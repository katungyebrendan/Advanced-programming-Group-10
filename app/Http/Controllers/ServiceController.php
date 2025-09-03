<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return view("services.index");
    }

    public function create()
    {
        return view("services.create");
    }

    public function store(Request $request)
    {
        return view("services.store");
    }

    public function show($id)
    {
        return view("show", ["id"=> $id]);
    }

    public function edit($id)
    {
        return view(view: "edit", data: ["id"=> $id]);
    }

    public function update(Request $request, $id)
    {
        return view("", ["id"=> $id]);
    }

    public function destroy($id)
    {
        return view("", ["id"=> $id]);
    }

    public function byFacility($facilityId)
    {
        return view("", [""=> $facilityId]);
    }

    public function search(Request $request)
    {
        return view("");
    }
}