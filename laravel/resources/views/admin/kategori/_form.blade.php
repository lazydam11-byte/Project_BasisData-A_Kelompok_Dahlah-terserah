@php
$nilai = old('nama_kategori', isset($kategori) ? $kategori->nama_kategori : '');
$cls = 'w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand ' . ($errors->has('nama_kategori') ? 'border-red-400' : 'border-slate-300');
@endphp

@if ($errors->any())
<div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
    Data belum bisa disimpan. Periksa kolom yang ditandai merah.
</div>
@endif

<div class="grid gap-4">
    <div>
        <label for="nama_kategori" class="mb-1 block text-sm font-semibold">Nama Kategori</label>
        <input id="nama_kategori" name="nama_kategori" value="{{ $nilai }}" maxlength="100" placeholder="Contoh: Smartphone" class="{{ $cls }}">
        @error('nama_kategori') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<p class="mt-4 text-xs text-slate-500">Satu kategori dapat mengelompokkan banyak produk. Nama kategori harus unik.</p>

<div class="mt-6 flex justify-end gap-2">
    <a href="{{ route('admin.kategori.index') }}" class="rounded-lg bg-slate-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-600">Batal</a>
    <button type="submit" class="rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2">Simpan</button>
</div>