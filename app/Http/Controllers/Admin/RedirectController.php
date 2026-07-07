<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function index()
    {
        $redirects = Redirect::orderBy('old_url')->paginate(25);
        return view('admin.redirects.index', compact('redirects'));
    }

    public function create()
    {
        return view('admin.redirects.form', ['redirect' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'old_url' => 'required|string|max:1024|unique:redirects,old_url',
            'new_url' => 'required|string|max:1024',
            'status_code' => 'required|in:301,302',
            'active' => 'boolean',
        ]);

        $data['active'] = $request->boolean('active', true);

        Redirect::create($data);

        return redirect()->route('admin.redirects.index')
            ->with('success', 'Redirect created.');
    }

    public function edit(Redirect $redirect)
    {
        return view('admin.redirects.form', compact('redirect'));
    }

    public function update(Request $request, Redirect $redirect)
    {
        $data = $request->validate([
            'old_url' => 'required|string|max:1024|unique:redirects,old_url,' . $redirect->id,
            'new_url' => 'required|string|max:1024',
            'status_code' => 'required|in:301,302',
            'active' => 'boolean',
        ]);

        $data['active'] = $request->boolean('active', true);

        $redirect->update($data);

        return redirect()->route('admin.redirects.index')
            ->with('success', 'Redirect updated.');
    }

    public function destroy(Redirect $redirect)
    {
        $redirect->delete();

        return redirect()->route('admin.redirects.index')
            ->with('success', 'Redirect deleted.');
    }
}
