<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbWave;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpdbWaveController extends Controller
{
    public function index(): View
    {
        return view('admin.ppdb.waves.index', [
            'waves' => PpdbWave::query()->orderBy('sort_order')->orderBy('mulai')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.ppdb.waves.form', ['wave' => new PpdbWave(['is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        PpdbWave::query()->create($validated);

        return redirect()->route('admin.ppdb-waves.index')->with('success', 'Gelombang berhasil ditambahkan.');
    }

    public function edit(PpdbWave $wave): View
    {
        return view('admin.ppdb.waves.form', ['wave' => $wave]);
    }

    public function update(Request $request, PpdbWave $wave): RedirectResponse
    {
        $validated = $this->validated($request);

        $wave->update($validated);

        return redirect()->route('admin.ppdb-waves.index')->with('success', 'Gelombang berhasil diperbarui.');
    }

    public function destroy(PpdbWave $wave): RedirectResponse
    {
        $wave->delete();

        return back()->with('success', 'Gelombang berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'mulai' => ['required', 'date'],
            'selesai' => ['required', 'date', 'after_or_equal:mulai'],
            'biaya' => ['nullable', 'integer', 'min:0'],
            'benefit' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
