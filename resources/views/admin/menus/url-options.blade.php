<datalist id="menu-url-options">
    <option value="/">Beranda</option>
    <option value="berita">Berita</option>
    <option value="pengumuman">Pengumuman</option>
    <option value="agenda">Agenda</option>
    <option value="tulisan-inspirasi">Tulisan Inspirasi</option>
    <option value="media-siswa">Media Siswa</option>
    <option value="foto">Foto &amp; Album</option>
    <option value="video">Video</option>
    <option value="majalah">Majalah</option>
    <option value="ppdb">Informasi PPDB</option>
    <option value="ppdb#formulir">Formulir PPDB</option>
    <option value="ekstrakurikuler">Ekstrakurikuler</option>
    <option value="hubungi-kami">Hubungi Kami</option>
    <option value="kerja-sama">Kerja Sama</option>
    <option value="faq">FAQ</option>
    <option value="sitemap.xml">Sitemap</option>

    @foreach ($halaman ?? collect() as $h)
        @if ($h->group === 'jurusan')
            <option value="jurusan/{{ $h->slug }}">{{ $h->title }} (Jurusan)</option>
        @else
            <option value="halaman/{{ $h->slug }}">{{ $h->title }}</option>
        @endif
    @endforeach
</datalist>
