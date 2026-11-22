<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbFee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpdbFeeController extends Controller
{
    public function index(): View
    {
        return view('admin.ppdb.fees.index', [
            'fees' => PpdbFee::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.ppdb.fees.form', ['fee' => new PpdbFee(['category' => 'BULANAN', 'is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        PpdbFee::query()->create($this->validated($request));

        return redirect()->route('admin.ppdb-fees.index')->with('success', 'Biaya berhasil ditambahkan.');
    }

    public function edit(PpdbFee $fee): View
    {
        return view('admin.ppdb.fees.form', ['fee' => $fee]);
    }

    public function update(Request $request, PpdbFee $fee): RedirectResponse
    {
        $fee->update($this->validated($request));

        return redirect()->route('admin.ppdb-fees.index')->with('success', 'Biaya berhasil diperbarui.');
    }

    public function destroy(PpdbFee $fee): RedirectResponse
    {
        $fee->delete();

        return back()->with('success', 'Biaya berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'label' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:32'],
            'description' => ['nullable', 'string', 'max:500'],
            'nominal' => ['required', 'integer', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
