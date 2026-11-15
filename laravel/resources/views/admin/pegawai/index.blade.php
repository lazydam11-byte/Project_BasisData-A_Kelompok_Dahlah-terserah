@extends('layouts.admin')
@section('title', 'Data Pegawai')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Kelola Data Master</h1>
    <p class="text-sm text-slate-500">Beranda › Data Master › Pegawai</p>
</div>

<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 bg-brand px-4 py-3 text-white">
        <h2 class="font-display font-bold">Data Pegawai</h2>
        <a href="{{ route('admin.pegawai.create') }}" class="rounded bg-aksen px-3 py-1.5 text-sm font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-white">+ Tambah Pegawai</a>
    </div>

    <form method="GET" class="flex flex-wrap items-end gap-3 border-b border-slate-200 p-4">
        <div class="min-w-[12rem] flex-1">
            <label for="q" class="mb-1 block text-xs font-semibold">Cari pegawai</label>
            <input id="q" name="q" value="{{ request('q') }}" placeholder="Nama atau username…" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
        </div>
        <div>
            <label for="jabatan" class="mb-1 block text-xs font-semibold">Jabatan</label>
            <select id="jabatan" name="jabatan" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                <option value="">Semua jabatan</option>
                @foreach ($daftarJabatan as $j)
                    <option value="{{ $j }}" @selected(request('jabatan') == $j)>{{ $j }}</option>
                @endforeach
            </select>
        </div>
        <button class="rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Cari</button>
        <a href="{{ route('admin.pegawai.index') }}" class="px-2 py-2 text-sm text-slate-500 hover:underline">Reset</a>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[40rem] text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-600">
                <tr>
                    <th class="px-4 py-2.5">ID</th>
                    <th class="px-4 py-2.5">Nama</th>
                    <th class="px-4 py-2.5">Username</th>
                    <th class="px-4 py-2.5">Jabatan</th>
                    <th class="px-4 py-2.5">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pegawai as $p)
                    @php $dirinya = $p->getKey() === auth('pegawai')->id(); @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-500">{{ $p->id_pegawai }}</td>
                        <td class="px-4 py-3 font-semibold">
                            {{ $p->nama }}
                            @if ($dirinya)
                                <span class="ml-1 rounded bg-slate-100 px-1.5 py-0.5 text-[11px] font-normal text-slate-500">Anda</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $p->username }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded px-2 py-0.5 text-xs font-semibold {{ $p->jabatan === 'Manajer' ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-700' }}">{{ $p->jabatan }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-1.5">
                                <a href="{{ route('admin.pegawai.edit', $p) }}" class="rounded bg-brand px-3 py-1 text-xs font-semibold text-white hover:bg-brand-dark">Ubah</a>
                                @unless ($dirinya)
                                    <form method="POST" action="{{ route('admin.pegawai.destroy', $p) }}" onsubmit="return confirm('Hapus pegawai {{ addslashes($p->nama) }}?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700">Hapus</button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada pegawai yang cocok. Ubah kata kunci atau reset filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($pegawai->hasPages())
        <div class="border-t border-slate-200 p-4">{{ $pegawai->links() }}</div>
    @endif
</section>
@endsection