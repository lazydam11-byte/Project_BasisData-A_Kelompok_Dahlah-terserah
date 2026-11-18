@extends('layouts.admin')
@section('title', 'Data Merek')

@section('content')
@php $bisaKelola = auth('pegawai')->user()->bisaKelolaProduk(); @endphp

<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Kelola Data Master</h1>
    <p class="text-sm text-slate-500">Beranda › Data Master › Merek</p>
</div>

<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 bg-brand px-4 py-3 text-white">
        <h2 class="font-display font-bold">Data Merek</h2>
        @if ($bisaKelola)
        <a href="{{ route('admin.merek.create') }}" class="rounded bg-aksen px-3 py-1.5 text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-white">+ Tambah Merek</a>
        @endif
    </div>

    <form method="GET" class="flex flex-wrap items-end gap-3 border-b border-slate-200 p-4">
        <div class="min-w-[12rem] flex-1">
            <label for="q" class="mb-1 block text-xs font-semibold">Cari merek</label>
            <input id="q" name="q" value="{{ request('q') }}" placeholder="Nama merek atau negara asal…" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
        </div>
        <button class="rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Cari</button>
        <a href="{{ route('admin.merek.index') }}" class="px-2 py-2 text-sm text-slate-500 hover:underline">Reset</a>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[40rem] text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-600">
                <tr>
                    <th class="px-4 py-2.5">ID Merek</th>
                    <th class="px-4 py-2.5">Nama Merek</th>
                    <th class="px-4 py-2.5">Negara Asal</th>
                    <th class="px-4 py-2.5">Jumlah Produk</th>
                    @if ($bisaKelola) <th class="px-4 py-2.5">Aksi</th> @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($merek as $m)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 text-slate-500">{{ $m->kode }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $m->nama_merek }}</td>
                    <td class="px-4 py-3">{{ $m->negara_asal }}</td>
                    <td class="px-4 py-3">{{ $m->produk_count }} produk</td>
                    @if ($bisaKelola)
                    <td class="px-4 py-3">
                        <div class="flex gap-1.5">
                            <a href="{{ route('admin.merek.edit', $m) }}" class="rounded bg-brand px-3 py-1 text-xs font-semibold text-white hover:bg-brand-dark">Ubah</a>
                            <form method="POST" action="{{ route('admin.merek.destroy', $m) }}" onsubmit="return confirm('Hapus merek {{ addslashes($m->nama_merek) }}?')">
                                @csrf @method('DELETE')
                                <button class="rounded bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700">Hapus</button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada merek yang cocok. Ubah kata kunci atau reset pencarian.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($merek->hasPages())
    <div class="border-t border-slate-200 p-4">{{ $merek->links() }}</div>
    @endif
</section>

@unless ($bisaKelola)
<p class="mt-3 text-xs text-slate-500">Jabatan Anda hanya dapat melihat data merek. Tambah, ubah, dan hapus dilakukan oleh Manajer atau Gudang.</p>
@endunless
@endsection