<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExtracurricularController extends Controller
{
    public function index(): View
    {
        return view('admin.extracurriculars.index', [
            'items' => Extracurricular::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.extracurriculars.form', ['item' => new Extracurricular(['is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Extracurricular::query()->create($this->validated($request));

        return redirect()->route('admin.extracurriculars.index')->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function edit(Extracurricular $extracurricular): View
    {
        return view('admin.extracurriculars.form', ['item' => $extracurricular]);
    }

    public function update(Request $request, Extracurricular $extracurricular): RedirectResponse
    {
        $extracurricular->update($this->validated($request));

        return redirect()->route('admin.extracurriculars.index')->with('success', 'Ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy(Extracurricular $extracurricular): RedirectResponse
    {
        $extracurricular->delete();

        return back()->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:64'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
