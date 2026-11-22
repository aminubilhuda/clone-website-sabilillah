<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Support\Uploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Label ringkas per tipe artikel.
     */
    public const TYPE_LABELS = [
        Post::TYPE_BERITA => 'Berita',
        Post::TYPE_PENGUMUMAN => 'Pengumuman',
        Post::TYPE_AGENDA => 'Agenda',
        Post::TYPE_TULISAN => 'Tulisan Inspirasi',
        Post::TYPE_MEDIA => 'Media Siswa',
        Post::TYPE_MAJALAH => 'Majalah',
        Post::TYPE_TOKOH => 'Tokoh/Testimoni',
    ];

    /**
     * Daftar artikel (dapat difilter per tipe).
     */
    public function index(Request $request): View
    {
        $type = $request->string('type')->toString();

        abort_if($type !== '' && ! array_key_exists($type, self::TYPE_LABELS), 404);

        $posts = Post::query()
            ->with(['category', 'author'])
            ->withTrashed()
            ->when($type !== '', fn ($query) => $query->where('type', $type))
            ->latest('published_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.posts.index', [
            'posts' => $posts,
            'type' => $type,
            'types' => self::TYPE_LABELS,
        ]);
    }

    /**
     * Form artikel baru.
     */
    public function create(Request $request): View
    {
        $type = $request->string('type')->toString();

        if ($type === '' || ! array_key_exists($type, self::TYPE_LABELS)) {
            $type = Post::TYPE_BERITA;
        }

        return view('admin.posts.form', [
            'post' => new Post(['type' => $type, 'status' => 'published']),
            'kategori' => $this->kategori($type),
            'types' => self::TYPE_LABELS,
            'jurusanOptions' => $this->jurusanOptions(),
        ]);
    }

    /**
     * Simpan artikel baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        $validated['slug'] = str($validated['title'])->slug()->limit(90)->toString().'-'.now()->format('ymdHis');
        $validated['user_id'] = $request->user()->id;
        $validated['published_at'] = ($validated['status'] ?? 'draft') === 'published'
            ? now()
            : null;
        $validated['is_featured'] = $request->boolean('is_featured');

        $post = Post::query()->create($validated);

        $this->simpanLampiran($request, $post);

        return redirect()->route('admin.posts.index', ['type' => $post->type])
            ->with('success', self::TYPE_LABELS[$post->type].' berhasil ditambahkan.');
    }

    /**
     * Form ubah artikel.
     */
    public function edit(Post $post): View
    {
        return view('admin.posts.form', [
            'post' => $post,
            'kategori' => $this->kategori($post->type),
            'types' => self::TYPE_LABELS,
            'jurusanOptions' => $this->jurusanOptions(),
        ]);
    }

    /**
     * Perbarui artikel.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $this->validateData($request);

        $validated['is_featured'] = $request->boolean('is_featured');

        if ($validated['status'] === 'published' && $post->published_at === null) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        $this->simpanLampiran($request, $post);

        return redirect()->route('admin.posts.index', ['type' => $post->type])
            ->with('success', self::TYPE_LABELS[$post->type].' berhasil diperbarui.');
    }

    /**
     * Hapus (lembut) artikel.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $type = $post->type;
        $post->delete();

        return back()->with('success', self::TYPE_LABELS[$type].' dipindahkan ke keranjang.');
    }

    /**
     * Pulihkan artikel dari keranjang.
     */
    public function restore(string $post): RedirectResponse
    {
        Post::onlyTrashed()->findOrFail($post)->restore();

        return back()->with('success', 'Artikel berhasil dipulihkan.');
    }

    /**
     * Ubah status terbit/arsip.
     */
    public function togglePublish(Post $post): RedirectResponse
    {
        if ($post->status === 'published') {
            $post->update(['status' => 'draft', 'published_at' => $post->published_at]);
        } else {
            $post->update(['status' => 'published', 'published_at' => $post->published_at ?? now()]);
        }

        return back()->with('success', 'Status artikel diperbarui.');
    }

    /**
     * Validasi data artikel.
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'type' => ['required', 'string', 'in:'.implode(',', array_keys(self::TYPE_LABELS))],
            'category_id' => ['nullable', 'exists:categories,id'],
            'jurusan' => ['nullable', 'string', Rule::in(array_keys($this->jurusanOptions()))],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:draft,published'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'location' => ['nullable', 'string', 'max:255'],
            'cover_image' => ['nullable', 'image', 'max:10240'],
            'document' => ['nullable', 'file', 'mimetypes:application/pdf', 'max:20480'],
        ]);
    }

    /**
     * Menyimpan unggahan gambar/dokumen artikel.
     */
    private function simpanLampiran(Request $request, Post $post): void
    {
        $cover = Uploads::file($request, 'cover_image', $post->cover_image);
        $document = Uploads::file($request, 'document', $post->document_path);

        $post->update([
            'cover_image' => $cover,
            'document_path' => $document,
        ]);
    }

    /**
     * Kategori sesuai tipe artikel.
     */
    private function kategori(string $type): Collection
    {
        return Category::query()->where('type', $type)->orderBy('name')->get();
    }

    /**
     * Pilihan jurusan (halaman group jurusan).
     *
     * @return array<string, string>
     */
    private function jurusanOptions(): array
    {
        return Page::query()
            ->where('group', 'jurusan')
            ->orderBy('sort_order')
            ->pluck('title', 'slug')
            ->all();
    }
}
