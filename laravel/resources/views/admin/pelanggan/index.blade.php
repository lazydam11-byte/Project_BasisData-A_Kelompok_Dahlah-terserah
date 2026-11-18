@extends('layouts.admin')
@section('title', 'Data Pelanggan')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Kelola Data Master</h1>
    <p class="text-sm text-slate-500">Beranda › Data Master › Pelanggan</p>
</div>

<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 bg-brand px-4 py-3 text-white">
        <h2 class="font-display font-bold">Data Pelanggan</h2>
        <a href="{{ route('admin.pelanggan.create') }}" class="rounded bg-aksen px-3 py-1.5 text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-white">+ Tambah Pelanggan</a>
    </div>

    <form method="GET" class="flex flex-wrap items-end gap-3 border-b border-slate-200 p-4">
        <div class="min-w-[12rem] flex-1">
            <label for="q" class="mb-1 block text-xs font-semibold">Cari pelanggan</label>
            <input id="q" name="q" value="{{ request('q') }}" placeholder="Nama, username, email, atau no. telepon…" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
        </div>
        <button class="rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Cari</button>
        <a href="{{ route('admin.pelanggan.index') }}" class="px-2 py-2 text-sm text-slate-500 hover:underline">Reset</a>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[48rem] text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-600">
                <tr>
                    <th class="px-4 py-2.5">ID</th>
                    <th class="px-4 py-2.5">Nama</th>
                    <th class="px-4 py-2.5">Kontak</th>
                    <th class="px-4 py-2.5">Alamat</th>
                    <th class="px-4 py-2.5">Username</th>
                    <th class="px-4 py-2.5">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pelanggan as $p)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-500">{{ $p->id_pelanggan }}</td>
                        <td class="px-4 py-3 font-semibold">{{ $p->nama }}</td>
                        <td class="px-4 py-3">
                            <p>{{ $p->no_telepon ?: '–' }}</p>
                            <p class="text-xs text-slate-500">{{ $p->email ?: '–' }}</p>
                        </td>
                        <td class="max-w-[16rem] px-4 py-3">{{ $p->alamat ?: '–' }}</td>
                        <td class="px-4 py-3">{{ $p->username }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.pelanggan.edit', $p) }}" class="rounded bg-brand px-3 py-1 text-xs font-semibold text-white hover:bg-brand-dark">Ubah</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">Belum ada pelanggan yang cocok. Ubah kata kunci atau reset filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($pelanggan->hasPages())
        <div class="border-t border-slate-200 p-4">{{ $pelanggan->links() }}</div>
    @endif
</section>
@endsection