<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    /**
     * Daftar FAQ.
     */
    public function index(): View
    {
        return view('admin.faqs.index', [
            'faqs' => Faq::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    /**
     * Form FAQ baru.
     */
    public function create(): View
    {
        return view('admin.faqs.form', ['faq' => new Faq]);
    }

    /**
     * Simpan FAQ.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        Faq::query()->create($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil ditambahkan.');
    }

    /**
     * Form ubah FAQ.
     */
    public function edit(Faq $faq): View
    {
        return view('admin.faqs.form', ['faq' => $faq]);
    }

    /**
     * Perbarui FAQ.
     */
    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $validated = $this->validateData($request);

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil diperbarui.');
    }

    /**
     * Hapus FAQ.
     */
    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return back()->with('success', 'FAQ berhasil dihapus.');
    }

    /**
     * Validasi data FAQ.
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:64'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
