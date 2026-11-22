<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbApplicant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PpdbApplicantController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $applicants = PpdbApplicant::query()
            ->with('wave')
            ->when($status !== '', fn ($q) => $q->where('status', $status))
            ->when($request->string('search')->toString(), fn ($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('nisn', 'like', "%{$s}%")
                    ->orWhere('asal_sekolah', 'like', "%{$s}%");
            }))
            ->latest()
            ->paginate(20);

        return view('admin.ppdb.applicants.index', [
            'applicants' => $applicants,
            'status' => $status,
        ]);
    }

    public function show(PpdbApplicant $applicant): View
    {
        return view('admin.ppdb.applicants.show', ['applicant' => $applicant->load('wave')]);
    }

    public function update(Request $request, PpdbApplicant $applicant): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:baru,diterima,ditolak'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $applicant->update($validated);

        return back()->with('success', 'Status pendaftar diperbarui.');
    }

    public function destroy(PpdbApplicant $applicant): RedirectResponse
    {
        $applicant->delete();

        return redirect()->route('admin.ppdb-applicants.index')->with('success', 'Pendaftar dihapus.');
    }
}
