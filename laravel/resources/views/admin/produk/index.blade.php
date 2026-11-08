@extends('layouts.admin')
@section('title', 'Data Produk')

@section('content')
@php $bisaKelola = auth('pegawai')->user()->bisaKelolaProduk(); @endphp

<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Kelola Data Master</h1>
    <p class="text-sm text-slate-500">Beranda › Data Master › Produk</p>
</div>

<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 bg-brand px-4 py-3 text-white">
        <h2 class="font-display font-bold">Data Produk</h2>
        @if ($bisaKelola)
        <a href="{{ route('admin.produk.create') }}" class="rounded bg-aksen px-3 py-1.5 text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-white">+ Tambah Produk</a>
        @endif
    </div>

    <form method="GET" class="flex flex-wrap items-end gap-3 border-b border-slate-200 p-4">
        <div class="min-w-[12rem] flex-1">
            <label for="q" class="mb-1 block text-xs font-semibold">Cari produk</label>
            <input id="q" name="q" value="{{ request('q') }}" placeholder="Nama produk…" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
        </div>
        <div>
            <label for="id_kategori" class="mb-1 block text-xs font-semibold">Kategori</label>
            <select id="id_kategori" name="id_kategori" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                <option value="">Semua kategori</option>
                @foreach ($daftarKategori as $k)
                <option value="{{ $k->id_kategori }}" @selected(request('id_kategori')==$k->id_kategori)>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <button class="rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Cari</button>
        <a href="{{ route('admin.produk.index') }}" class="px-2 py-2 text-sm text-slate-500 hover:underline">Reset</a>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[56rem] text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-600">
                <tr>
                    <th class="px-4 py-2.5">ID Produk</th>
                    <th class="px-4 py-2.5">Foto</th>
                    <th class="px-4 py-2.5">Nama Produk</th>
                    <th class="px-4 py-2.5">Kategori</th>
                    <th class="px-4 py-2.5">Merek</th>
                    <th class="px-4 py-2.5">Harga (Rp)</th>
                    <th class="px-4 py-2.5">Stok</th>
                    <th class="px-4 py-2.5">Garansi</th>
                    <th class="px-4 py-2.5">Tgl Masuk</th>
                    @if ($bisaKelola) <th class="px-4 py-2.5">Aksi</th> @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($produk as $p)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 text-slate-500">{{ $p->kode }}</td>
                    <td class="px-4 py-3">
                        @if ($p->foto_url)
                        <img src="{{ $p->foto_url }}" alt="{{ $p->nama_produk }}" class="h-10 w-10 rounded object-cover">
                        @else
                        <div class="flex h-10 w-10 items-center justify-center rounded bg-slate-100 text-[10px] text-slate-400">–</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-semibold">{{ $p->nama_produk }}</td>
                    <td class="px-4 py-3">{{ $p->kategori->nama_kategori }}</td>
                    <td class="px-4 py-3">{{ $p->merek->nama_merek }}</td>
                    <td class="px-4 py-3">{{ number_format($p->harga, 0, ',', '.') }}</td>
                    <td class="px-4 py-3"><span class="rounded px-2 py-0.5 text-xs font-semibold {{ $p->status_stok[1] }}">{{ $p->stok }}</span></td>
                    <td class="px-4 py-3">{{ $p->text_garansi }}</td>
                    <td class="px-4 py-3">{{ $p->tanggal_masuk->format('d/m/Y') }}</td>
                    @if ($bisaKelola)
                    <td class="px-4 py-3">
                        <div class="flex gap-1.5">
                            <a href="{{ route('admin.produk.edit', $p) }}" class="rounded bg-brand px-3 py-1 text-xs font-semibold text-white hover:bg-brand-dark">Ubah</a>
                            <form method="POST" action="{{ route('admin.produk.destroy', $p) }}" onsubmit="return confirm('Hapus produk {{ addslashes($p->nama_produk) }}?')">
                                @csrf @method('DELETE')
                                <button class="rounded bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700">Hapus</button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-4 py-10 text-center text-slate-500">Belum ada produk yang cocok. Ubah kata kunci atau reset filter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($produk->hasPages())
    <div class="border-t border-slate-200 p-4">{{ $produk->links() }}</div>
    @endif
</section>

@unless ($bisaKelola)
<p class="mt-3 text-xs text-slate-500">Jabatan Anda hanya dapat melihat data produk. Tambah, ubah, dan hapus dilakukan oleh Manajer atau Gudang.</p>
@endunless
@endsection