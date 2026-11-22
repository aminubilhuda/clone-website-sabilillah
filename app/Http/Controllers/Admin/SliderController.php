<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlider;
use App\Support\Uploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SliderController extends Controller
{
    /**
     * Menampilkan daftar slider hero.
     */
    public function index(): View
    {
        return view('admin.sliders.index', [
            'sliders' => HeroSlider::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    /**
     * Form slider baru.
     */
    public function create(): View
    {
        return view('admin.sliders.form', ['slider' => new HeroSlider]);
    }

    /**
     * Menyimpan slider baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        $validated['image'] = Uploads::file($request, 'image');

        if (blank($validated['image'])) {
            return back()->withErrors(['image' => 'Gambar slider wajib diunggah.'])->withInput();
        }

        HeroSlider::query()->create($validated);

        return redirect()->route('admin.hero-sliders.index')
            ->with('success', 'Slider berhasil ditambahkan.');
    }

    /**
     * Form ubah slider.
     */
    public function edit(HeroSlider $slider): View
    {
        return view('admin.sliders.form', ['slider' => $slider]);
    }

    /**
     * Memperbarui slider.
     */
    public function update(Request $request, HeroSlider $slider): RedirectResponse
    {
        $validated = $this->validateData($request);

        $validated['image'] = Uploads::file($request, 'image', $slider->image);

        $slider->update($validated);

        return redirect()->route('admin.hero-sliders.index')
            ->with('success', 'Slider berhasil diperbarui.');
    }

    /**
     * Menghapus slider.
     */
    public function destroy(HeroSlider $slider): RedirectResponse
    {
        Uploads::delete($slider->image);

        $slider->delete();

        return back()->with('success', 'Slider berhasil dihapus.');
    }

    /**
     * Validasi dasar data slider.
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:10240'],
        ]);
    }
}
