@extends('layouts.admin')

@section('title', $faq->exists ? 'Ubah FAQ' : 'FAQ Baru')

@section('konten')
    <h1 class="h3 mb-4">{{ $faq->exists ? 'Ubah FAQ' : 'FAQ Baru' }}</h1>

    <div class="card admin-card" style="max-width: 640px;">
        <div class="card-body p-4">
            <form action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" method="POST">
                @csrf
                @if ($faq->exists)
                    @method('PUT')
                @endif

                <label class="form-label" for="faq-q">Pertanyaan *</label>
                <input type="text" id="faq-q" name="question" value="{{ old('question', $faq->question) }}" class="form-control" required>

                <label class="form-label mt-3" for="faq-a">Jawaban *</label>
                <textarea id="faq-a" name="answer" rows="5" class="form-control" required>{{ old('answer', $faq->answer) }}</textarea>

                <label class="form-label mt-3" for="faq-c">Kategori</label>
                <input type="text" id="faq-c" name="category" value="{{ old('category', $faq->category) }}" class="form-control">

                <div class="form-check mt-3">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" id="faq-i" name="is_active" value="1"
                        {{ old('is_active', $faq->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="faq-i">Aktif</label>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-dark rounded-pill">Kembali</a>
                    <button type="submit" class="btn btn-pill-ppdb">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
