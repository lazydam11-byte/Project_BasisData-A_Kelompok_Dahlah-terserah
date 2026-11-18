@extends('layouts.admin')
@section('title', 'Data Kategori')

@section('content')
@php $bisaKelola = auth('pegawai')->user()->bisaKelolaProduk(); @endphp

<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Kelola Data Master</h1>
    <p class="text-sm text-slate-500">Beranda › Data Master › Kategori</p>
</div>

<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 bg-brand px-4 py-3 text-white">
        <h2 class="font-display font-bold">Data Kategori</h2>
        @if ($bisaKelola)
        <a href="{{ route('admin.kategori.create') }}" class="rounded bg-aksen px-3 py-1.5 text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-white">+ Tambah Kategori</a>
        @endif
    </div>

    <form method="GET" class="flex flex-wrap items-end gap-3 border-b border-slate-200 p-4">
        <div class="min-w-[12rem] flex-1">
            <label for="q" class="mb-1 block text-xs font-semibold">Cari kategori</label>
            <input id="q" name="q" value="{{ request('q') }}" placeholder="Nama kategori…" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
        </div>
        <button class="rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Cari</button>
        <a href="{{ route('admin.kategori.index') }}" class="px-2 py-2 text-sm text-slate-500 hover:underline">Reset</a>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[32rem] text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-600">
                <tr>
                    <th class="px-4 py-2.5">ID Kategori</th>
                    <th class="px-4 py-2.5">Nama Kategori</th>
                    <th class="px-4 py-2.5">Jumlah Produk</th>
                    @if ($bisaKelola) <th class="px-4 py-2.5">Aksi</th> @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($kategori as $k)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 text-slate-500">{{ $k->kode }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $k->nama_kategori }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.produk.index', ['id_kategori' => $k->id_kategori]) }}" class="text-brand hover:underline">{{ $k->produk_count }} produk</a>
                    </td>
                    @if ($bisaKelola)
                    <td class="px-4 py-3">
                        <div class="flex gap-1.5">
                            <a href="{{ route('admin.kategori.edit', $k) }}" class="rounded bg-brand px-3 py-1 text-xs font-semibold text-white hover:bg-brand-dark">Ubah</a>
                            <form method="POST" action="{{ route('admin.kategori.destroy', $k) }}" onsubmit="return confirm('Hapus kategori {{ addslashes($k->nama_kategori) }}?')">
                                @csrf @method('DELETE')
                                <button class="rounded bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700">Hapus</button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-10 text-center text-slate-500">Belum ada kategori yang cocok. Ubah kata kunci atau reset pencarian.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($kategori->hasPages())
    <div class="border-t border-slate-200 p-4">{{ $kategori->links() }}</div>
    @endif
</section>

@unless ($bisaKelola)
<p class="mt-3 text-xs text-slate-500">Jabatan Anda hanya dapat melihat data kategori. Tambah, ubah, dan hapus dilakukan oleh Manajer atau Gudang.</p>
@endunless
@endsection