<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Daftar pengguna panel.
     */
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()->latest('id')->paginate(15),
        ]);
    }

    /**
     * Form pengguna baru.
     */
    public function create(): View
    {
        return view('admin.users.form', ['user' => new User(['role' => 'editor'])]);
    }

    /**
     * Simpan pengguna.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        User::query()->create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Form ubah pengguna.
     */
    public function edit(User $user): View
    {
        return view('admin.users.form', ['user' => $user]);
    }

    /**
     * Perbarui pengguna.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $this->validated($request);
        $passwordChanged = filled($validated['password'] ?? null);

        if (! $passwordChanged) {
            unset($validated['password']);
        }

        $user->update($validated);

        if ($passwordChanged) {
            $user->setRememberToken(Str::random(60));
            $user->save();
        }

        if ($passwordChanged && config('session.driver') === 'database') {
            $query = DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->id);

            if ($request->user()->is($user)) {
                $query->where('id', '!=', $request->session()->getId());
            }

            $query->delete();
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Hapus pengguna.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Validasi data pengguna (password opsional saat edit).
     */
    private function validated(Request $request): array
    {
        $isCreate = $request->isMethod('POST') && $request->route()->parameter('user') === null;

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', 'alpha_dash', 'unique:users,username'.($isCreate ? '' : ','.$request->route('user')->id)],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'.($isCreate ? '' : ','.$request->route('user')->id)],
            'password' => [$isCreate ? 'required' : 'sometimes', 'nullable', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:admin,editor'],
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
            'username.unique' => 'Username sudah digunakan.',
        ]);
    }
}
