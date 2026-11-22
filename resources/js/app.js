/* =========================================================
   SMK Abdi Negara Tuban — JS entri aplikasi
   Fondasi: jQuery, Bootstrap 5, Owl Carousel, WOW.js
   Perilaku: loader, popup sekali/kunjungan, marquee, toggle
   teks, carousel, tombol ke atas, dan gaya navbar saat gulir.
   ========================================================= */

// jQuery harus tersedia sebagai global sebelum plugin (Owl) dimuat.
const $ = (await import('jquery')).default;
window.$ = window.jQuery = $;

const bootstrap = await import('bootstrap');
await import('owl.carousel');

// WOW.js: memicu animasi masuk data-wow-*
try {
    const wowModule = await import('wowjs');
    const WOW = wowModule.default ?? wowModule;

    new WOW({ live: false, offset: 60 }).init();
} catch (error) {
    // Animasi tetap berfungsi tanpa WOW (konten langsung tampil).
}

$(document).ready(() => {
    /* ---------- Loader ---------- */
    const hilangkanLoader = (message = 'heroSliderReady') => {
        const $loader = $('#loader');
        $loader.addClass('spinner-hidden');
        setTimeout(() => $loader.remove(), 550);

        $(document).trigger(message);
    };

    const $slidePertama = $('.owl-carousel.hero-carousel .owl-item:not(.cloned) img.slider-media').first();

    if ($slidePertama.length) {
        if ($slidePertama[0].complete) {
            setTimeout(hilangkanLoader, 300);
        } else {
            $slidePertama.on('load', () => hilangkanLoader());
            $slidePertama.on('error', () => hilangkanLoader());
        }
    }

    setTimeout(() => hilangkanLoader(), 2500);

    /* ---------- Carousel Owl ---------- */
    $('.owl-carousel.hero-carousel').owlCarousel({
        items: 1,
        nav: false,
        dots: true,
        autoplay: true,
        autoplayHoverPause: true,
        autoplayTimeout: 5000,
        smartSpeed: 1000,
        loop: true,
        animateOut: 'fadeOut',
        mouseDrag: false,
        touchDrag: false,
    });

    $('.owl-carousel.berita-carousel').owlCarousel({
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        smartSpeed: 900,
        margin: 25,
        dots: false,
        loop: true,
        responsive: {
            0: { items: 1 },
            576: { items: 1 },
            768: { items: 2 },
            992: { items: 3 },
            1200: { items: 4 },
        },
    });

    $('.owl-carousel.agenda-carousel').owlCarousel({
        autoplay: true,
        autoplayTimeout: 9000,
        autoplayHoverPause: true,
        smartSpeed: 800,
        margin: 12,
        nav: false,
        dots: true,
        items: 1,
    });

    $('.owl-carousel.tulisan-carousel').owlCarousel({
        autoplay: true,
        autoplayTimeout: 3500,
        autoplayHoverPause: true,
        smartSpeed: 850,
        margin: 16,
        dots: true,
        responsive: {
            0: { items: 1 },
            768: { items: 1 },
            992: { items: 2 },
        },
    });

    $('.owl-carousel.highlight-carousel').owlCarousel({
        autoplay: true,
        autoplayTimeout: 2500,
        autoplayHoverPause: true,
        smartSpeed: 950,
        margin: 24,
        dots: true,
        responsive: {
            0: { items: 1 },
            768: { items: 2 },
            992: { items: 3 },
            1200: { items: 4 },
        },
    });

    $('.owl-carousel.testimoni-carousel').owlCarousel({
        autoplay: true,
        autoplayTimeout: 4200,
        autoplayHoverPause: true,
        smartSpeed: 900,
        margin: 30,
        dots: true,
        responsive: {
            0: { items: 1 },
            768: { items: 2 },
        },
    });

    /* ---------- Popup PPDB (sekali per kunjungan) ---------- */
    const popupElement = document.getElementById('myPopup');

    if (popupElement && sessionStorage.getItem('sabil-popup-dismissed') !== '1') {
        setTimeout(() => {
            const popup = bootstrap.Modal.getOrCreateInstance(popupElement);
            popup.show();

            popupElement.addEventListener('hidden.bs.modal', () => {
                sessionStorage.setItem('sabil-popup-dismissed', '1');
            }, { once: true });
        }, 1500);
    }

    /* ---------- Marquee ---------- */
    $('.js-marquee-track').each((_i, el) => {
        const track = $(el);
        const $group = track.find('.logo-marquee__group').first();

        if (!$group.length) {
            return;
        }

        // Duplikasi konten untuk paket seamless
        $group.clone().css({ flex: '0 0 auto' }).appendTo(track);
    });

    /* ---------- Toggle teks sekilas ---------- */
    const $toggle = $('.toggle-text');
    const $target = $('.toggle-target');

    if ($toggle.length && $target.length) {
        const wrap = $target.closest('.text-toggle-wrap');
        const maxLength = Number(wrap.data('max') || 0);

        // Jika konten pendek, sembunyikan tombol
        if ($target.text().trim().length <= 300) {
            $toggle.hide();
        } else {
            $toggle.on('click', function () {
                const isExpanding = !wrap.hasClass('expanded');

                if (maxLength > 0) {
                    const plain = $target.text().trim();

                    if (isExpanding) {
                        if (!wrap.find('.teks-lengkap').length) {
                            $('<p class="lead teks-lengkap mt-2 d-none"></p>').insertAfter($target);
                        }

                        wrap.find('.teks-lengkap').text(plain).removeClass('d-none');
                    } else {
                        wrap.find('.teks-lengkap').toggleClass('d-none');
                    }
                } else {
                    $target.toggleClass('prolonged');
                }

                wrap.toggleClass('expanded', isExpanding);
                $(this).attr('aria-expanded', String(isExpanding));
                $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
                $(this).find('span').first().text(isExpanding ? 'Tutup' : 'Selengkapnya');
            });
        }
    }

    /* ---------- Tombol kembali ke atas ---------- */
    const $btnAtas = $('#btn-atas');

    $(window).on('scroll', () => {
        $btnAtas.toggleClass('aktif', $(window).scrollTop() > 280);
        $('.main-navbar').toggleClass('shadow-lg', $(window).scrollTop() > 60);
    });

    $btnAtas.on('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    /* ---------- Rapikan aria pada popup ---------- */
    $('.sabil-popup').on('shown.bs.modal', function () {
        $(this).removeAttr('aria-hidden');
    });
});
