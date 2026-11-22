<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbRequirement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpdbRequirementController extends Controller
{
    public function index(): View
    {
        return view('admin.ppdb.requirements.index', [
            'umum' => PpdbRequirement::query()->ofType('umum')->get(),
            'berkas' => PpdbRequirement::query()->ofType('berkas')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'in:umum,berkas'],
            'content' => ['required', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        PpdbRequirement::query()->create($validated);

        return back()->with('success', 'Persyaratan berhasil ditambahkan.');
    }

    public function update(Request $request, PpdbRequirement $requirement): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $requirement->update($validated);

        return back()->with('success', 'Persyaratan berhasil diperbarui.');
    }

    public function destroy(PpdbRequirement $requirement): RedirectResponse
    {
        $requirement->delete();

        return back()->with('success', 'Persyaratan berhasil dihapus.');
    }
}
