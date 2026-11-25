@php
    $inputId = $id ?? 'media-input-'.str_replace(['_', '.'], '-', $name);
    $currentPath = $current ?? null;
    $compactMode = $compact ?? false;
    $acceptAttr = $accept ?? 'image/png,image/jpeg,image/webp,image/gif';
@endphp

<div class="media-picker" data-media-picker>
    <div class="mb-2 d-flex align-items-center gap-2 {{ $compactMode ? '' : 'flex-wrap' }}" data-media-preview-wrap style="{{ filled($currentPath) ? '' : 'display:none !important;' }}">
        <img src="{{ filled($currentPath) ? media_url($currentPath) : '' }}"
            alt="Pratinjau"
            data-media-preview
            class="rounded border object-fit-cover"
            style="height: {{ $compactMode ? '44px' : '72px' }}; max-width: 160px;">
        <span class="badge text-bg-light border small d-none" data-media-badge></span>
    </div>

    <div class="input-group {{ $compactMode ? 'input-group-sm' : '' }}">
        <input type="file"
            id="{{ $inputId }}"
            name="{{ $name }}"
            accept="{{ $acceptAttr }}"
            data-media-file
            class="form-control @error($name) is-invalid @enderror">
        <button type="button"
            class="btn btn-outline-primary"
            data-bs-toggle="modal"
            data-bs-target="#mediaPickerModal"
            data-media-open>
            <i class="fa fa-images me-1"></i>Pilih Media
        </button>
    </div>

    <input type="hidden" name="{{ $name }}_existing" value="" data-media-existing>

    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
    @if (!empty($hint))
        <div class="form-text">{{ $hint }}</div>
    @endif
</div>
