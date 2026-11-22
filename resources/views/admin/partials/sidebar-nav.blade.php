{{-- Navigasi sidebar admin, dikelompokkan per kategori --}}
<a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="fa fa-tachometer-alt"></i> Dashboard
</a>

@foreach ($ruteKonten as $grup => $items)
    <span class="admin-nav-label">{{ $grup }}</span>

    @foreach ($items as $rute)
        <a href="{{ $rute['url'] }}" class="nav-link {{ request()->fullUrlIs($rute['aktif']) ? 'active' : '' }}">
            <i class="fa {{ $rute['icon'] }}"></i> {{ $rute['label'] }}
        </a>
    @endforeach
@endforeach

@can('manage-users')
    <span class="admin-nav-label">Sistem</span>

    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="fa fa-users-cog"></i> Pengguna
    </a>
@endcan
