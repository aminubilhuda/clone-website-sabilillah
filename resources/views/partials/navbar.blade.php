<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top main-navbar">
    <div class="container">
        <a class="navbar-brand py-0 d-lg-none" href="{{ route('home') }}">
            <img src="{{ media_url(setting('logo'), 'img/logo-sekolah.svg') }}" alt="Logo {{ setting('nama_sekolah') }}" class="logo-sapii">
        </a>

        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUtama"
            aria-controls="navbarUtama" aria-expanded="false" aria-label="Buka menu navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarUtama">
            <a class="navbar-brand py-0 d-none d-lg-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ media_url(setting('logo'), 'img/logo-sekolah.svg') }}" alt="Logo {{ setting('nama_sekolah') }}" class="logo-sapii me-2">
                <span class="brand-nama">{{ setting('nama_sekolah', 'SMK Abdi Negara Tuban') }}</span>
            </a>

            <ul class="navbar-nav ms-auto align-items-lg-center">
                @foreach ($sharedMenus as $menu)
                    <li class="nav-item {{ $menu->children->count() ? 'dropdown' : '' }} dropdown-mega">
                        <a class="nav-link {{ $menu->children->count() ? 'dropdown-toggle' : '' }}"
                            href="{{ str_starts_with($menu->url, 'http') ? $menu->url : url($menu->url) }}"
                            {{ $menu->children->count() ? 'data-bs-toggle="dropdown" aria-expanded="false"' : '' }}>
                            {{ $menu->label }}
                        </a>

                        @if ($menu->children->count())
                            <div class="dropdown-menu dropdown-megau shadow border-0 m-0">
                                <ul class="megau-list list-unstyled m-0 p-0">
                                    @foreach ($menu->children as $child)
                                        <li>
                                            <a class="dropdown-item py-2"
                                                href="{{ str_starts_with($child->url, 'http') ? $child->url : url($child->url) }}">
                                                <i class="fa fa-chevron-right me-2 text-primary small"></i>{{ $child->label }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </li>
                @endforeach

                <li class="nav-item ms-lg-3 my-2 my-lg-0">
                    <a href="{{ url(setting('banner_link', 'halaman/tata-cara-pendaftaran')) }}"
                        class="btn btn-pill-ppdb px-3 btn-sm rounded-pill">
                        {{ setting('navbar_ppdb_text', 'PPDB Dibuka') }}
                    </a>
                </li>

                <li class="nav-item ms-lg-2">
                    @auth
                        @if (in_array(auth()->user()->role, ['admin', 'editor'], true))
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-login">
                                <i class="fa fa-user me-1"></i> PANEL
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-login">
                            <i class="far fa-user me-1"></i> LOGIN
                        </a>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>
