@if ($sharedPopup)
    <div class="modal fade sabil-popup" id="myPopup" tabindex="-1" aria-labelledby="myPopupLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bg-transparent shadow-none">
                <div class="text-end pb-2">
                    <a href="javascript:void(0)" data-bs-dismiss="modal" class="sabil-popup-close" aria-label="Tutup"><i class="fa fa-times-circle"></i></a>
                </div>
                <a href="{{ str_starts_with($sharedPopup->link, 'http') ? $sharedPopup->link : url($sharedPopup->link) }}">
                    <img src="{{ media_url($sharedPopup->image, 'img/popup-ppdb.svg') }}" alt="Popup {{ setting('nama_sekolah') }}" class="img-fluid rounded-3">
                </a>
            </div>
        </div>
    </div>
@endif
