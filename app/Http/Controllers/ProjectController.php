<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        // Mock data or actual data query
        return view('projects.index');
    }

    public function show($slug)
    {
        return view('projects.show', compact('slug'));
    }
}
