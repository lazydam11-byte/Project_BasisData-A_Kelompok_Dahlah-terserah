@extends('layouts.katalog')
@section('title', 'Semua Produk')

@section('content')
@php
    $pelanggan = auth('pelanggan')->user();
@endphp


{{-- HEADER KATALOG --}}
<section class="relative mb-8 overflow-hidden rounded-2xl bg-gradient-to-br from-brand-dark via-brand to-blue-700 p-6 text-white shadow-lg md:p-8">

    <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-blue-400/20 blur-2xl"></div>
    <div class="absolute -bottom-20 right-1/3 h-48 w-48 rounded-full bg-blue-300/20 blur-3xl"></div>

    <div class="relative">
        @if ($pelanggan)
            <p class="mb-2 text-sm font-medium text-blue-200">
                Halo, {{ $pelanggan->nama }}. Selamat berbelanja!
            </p>
        @else
            <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-medium text-blue-100">
                <i class="fa-solid fa-bag-shopping text-orange-400"></i>
                Gadgetkuori Online Store
            </span>
        @endif

        <h1 class="mt-3 font-display text-3xl font-extrabold text-white md:text-4xl">
            Temukan Gadget
            <span class="text-blue-300">Favoritmu!</span>
        </h1>

        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-blue-100 md:text-base">
            Jelajahi berbagai pilihan gadget berkualitas dari berbagai merek.
            Temukan produk yang sesuai kebutuhanmu dan belanja dengan mudah melalui website kami.
        </p>

        <div class="mt-5 flex flex-wrap gap-3">
            <div class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/10 px-4 py-2 text-sm text-white">
                <i class="fa-solid fa-mobile-screen-button text-blue-300"></i>
                Beragam pilihan gadget
            </div>

            <div class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/10 px-4 py-2 text-sm text-white">
                <i class="fa-solid fa-shield-halved text-blue-300"></i>
                Produk bergaransi
            </div>
        </div>
    </div>
</section>

{{-- PENCARIAN DAN FILTER --}}
<section class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-100 px-5 py-4">
        <h2 class="font-display font-bold text-slate-800">
            <i class="fa-solid fa-sliders mr-2 text-brand"></i>
            Cari dan Filter Produk
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            Gunakan pencarian dan filter untuk menemukan produk yang kamu inginkan.
        </p>
    </div>

    <form method="GET" class="p-5">

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">

            {{-- PENCARIAN --}}
            <div class="lg:col-span-2">
                <label for="q" class="mb-2 block text-xs font-bold text-slate-700">
                    Cari produk
                </label>

                <div class="flex items-center rounded-xl border border-slate-300 px-3 transition focus-within:border-brand focus-within:ring-2 focus-within:ring-brand/20">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>

                    <input id="q"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari nama produk, misalnya Galaxy S24..."
                        class="w-full border-0 bg-transparent px-3 py-3 text-sm text-slate-700 outline-none focus:ring-0">
                </div>
            </div>

            {{-- KATEGORI --}}
            <div>
                <label for="id_kategori" class="mb-2 block text-xs font-bold text-slate-700">
                    Kategori
                </label>

                <select id="id_kategori"
                    name="id_kategori"
                    class="w-full rounded-xl border border-slate-300 px-3 py-3 text-sm text-slate-700 outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/20">

                    <option value="">Semua kategori</option>

                    @foreach ($daftarKategori as $k)
                        <option value="{{ $k->id_kategori }}"
                            @selected(request('id_kategori') == $k->id_kategori)>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- MEREK --}}
            <div>
                <label for="id_merek" class="mb-2 block text-xs font-bold text-slate-700">
                    Merek
                </label>

                <select id="id_merek"
                    name="id_merek"
                    class="w-full rounded-xl border border-slate-300 px-3 py-3 text-sm text-slate-700 outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/20">

                    <option value="">Semua merek</option>

                    @foreach ($daftarMerek as $m)
                        <option value="{{ $m->id_merek }}"
                            @selected(request('id_merek') == $m->id_merek)>
                            {{ $m->nama_merek }}
                        </option>
                    @endforeach

                </select>
            </div>

        </div>

        <div class="mt-5 flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 pt-4">

            <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                <input type="checkbox"
                    name="stok_tersedia"
                    value="1"
                    @checked(request()->boolean('stok_tersedia'))
                    class="h-4 w-4 rounded border-slate-300 text-brand focus:ring-brand">

                Hanya tampilkan stok tersedia
            </label>

            <div class="flex w-full gap-2 sm:w-auto">

                <a href="{{ route('katalog.index') }}"
                    class="flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-100 sm:flex-none">
                    Reset
                </a>

                <button type="submit"
                    class="flex-1 rounded-xl bg-brand px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-dark sm:flex-none">
                    <i class="fa-solid fa-filter mr-2"></i>
                    Terapkan Filter
                </button>

            </div>
        </div>

    </form>
</section>


{{-- INFORMASI JUMLAH PRODUK --}}
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">

    <div>
        <h2 class="font-display text-xl font-bold text-slate-800">
            Semua Produk
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Temukan produk terbaik untuk kebutuhanmu.
        </p>
    </div>

    <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-brand">
        <i class="fa-solid fa-box-open"></i>
        {{ $produk->total() }} Produk
    </div>

</div>


{{-- DAFTAR PRODUK --}}
@if ($produk->isEmpty())

    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm">

        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-100">
            <i class="fa-solid fa-magnifying-glass text-3xl text-slate-400"></i>
        </div>

        <h2 class="mt-5 font-display text-xl font-bold text-slate-800">
            Produk Tidak Ditemukan
        </h2>

        <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
            Maaf, produk yang kamu cari belum tersedia.
            Coba gunakan kata kunci lain atau ubah filter pencarian.
        </p>

        <a href="{{ route('katalog.index') }}"
            class="mt-5 inline-flex items-center gap-2 rounded-xl bg-brand px-5 py-3 text-sm font-semibold text-white transition hover:bg-brand-dark">
            <i class="fa-solid fa-rotate-left"></i>
            Reset Pencarian
        </a>

    </div>

@else

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

        @foreach ($produk as $p)

            <article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl">

                {{-- GAMBAR PRODUK --}}
                <div class="relative overflow-hidden bg-slate-50">

                    @if ($p->foto_url)

                        <img src="{{ $p->foto_url }}"
                            alt="{{ $p->nama_produk }}"
                            loading="lazy"
                            class="h-52 w-full object-contain p-4 transition duration-500 group-hover:scale-105">

                    @else

                        <div class="flex h-52 flex-col items-center justify-center text-slate-400">
                            <i class="fa-solid fa-image text-4xl"></i>
                            <p class="mt-2 text-xs">Belum ada foto</p>
                        </div>

                    @endif

                    {{-- STATUS STOK --}}
                    <span class="absolute left-3 top-3 inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold shadow-sm {{ $p->status_stok[1] }}">
                        {{ $p->status_stok[0] }}
                    </span>

                </div>


                {{-- INFORMASI PRODUK --}}
                <div class="flex flex-1 flex-col p-4">

                    <div class="mb-2 flex items-center gap-2 text-xs text-slate-500">
                        <i class="fa-solid fa-tag text-brand"></i>
                        {{ $p->merek->nama_merek }}

                        <span>·</span>

                        {{ $p->kategori->nama_kategori }}
                    </div>

                    <h3 class="line-clamp-2 min-h-12 font-display text-base font-bold leading-snug text-slate-800 transition group-hover:text-brand">
                        {{ $p->nama_produk }}
                    </h3>

                    <div class="mt-2 flex items-center gap-2 text-xs text-slate-500">
                        <i class="fa-solid fa-shield-halved text-brand"></i>
                        Garansi: {{ $p->text_garansi }}
                    </div>

                    {{-- HARGA --}}
                    <div class="mt-auto border-t border-slate-100 pt-4">

                        <p class="text-xs text-slate-400">
                            Harga produk
                        </p>

                        <p class="mt-1 font-display text-xl font-extrabold text-brand">
                            {{ $p->harga_rupiah }}
                        </p>

                        {{-- TOMBOL KERANJANG --}}
                        @if ($p->stok > 0)

                            <form method="POST"
                                action="{{ route('keranjang.tambah', $p) }}"
                                class="mt-4">

                                @csrf

                                <button type="submit"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-aksen px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90 active:scale-[0.98]">

                                    <i class="fa-solid fa-cart-plus"></i>
                                    Tambah ke Keranjang

                                </button>

                            </form>

                        @else

                            <button type="button"
                                disabled
                                class="mt-4 w-full cursor-not-allowed rounded-xl bg-slate-200 px-4 py-3 text-sm font-semibold text-slate-500">

                                <i class="fa-solid fa-ban mr-1"></i>
                                Stok Habis

                            </button>

                        @endif

                    </div>

                </div>

            </article>

        @endforeach

    </div>


    {{-- PAGINATION --}}
    @if ($produk->hasPages())

        <div class="mt-8 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            {{ $produk->withQueryString()->links() }}
        </div>

    @endif

@endif


{{-- FOOTER INFORMASI --}}
<div class="mt-10 flex flex-col items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 px-5 py-6 text-center">

    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white text-brand shadow-sm">
        <i class="fa-solid fa-heart text-xl"></i>
    </div>

    <h3 class="font-display font-bold text-slate-800">
        Selamat Berbelanja di Gadgetkuori!
    </h3>

    <p class="max-w-lg text-sm text-slate-500">
        Temukan gadget yang sesuai dengan kebutuhanmu dan nikmati pengalaman berbelanja yang mudah dan praktis.
    </p>

</div>

@endsection