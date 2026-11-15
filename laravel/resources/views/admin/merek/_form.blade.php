@php
    $field = fn ($name) => old($name, isset($merek) ? $merek->{$name} : '');
    $cls = fn ($name) => 'w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand ' . ($errors->has($name) ? 'border-red-400' : 'border-slate-300');
@endphp

@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
        Data belum bisa disimpan. Periksa kolom yang ditandai merah.
    </div>
@endif

<div class="grid gap-4">
    <div>
        <label for="nama_merek" class="mb-1 block text-sm font-semibold">Nama Merek</label>
        <input id="nama_merek" name="nama_merek" value="{{ $field('nama_merek') }}" maxlength="100" placeholder="Contoh: Samsung" class="{{ $cls('nama_merek') }}">
        @error('nama_merek') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="negara_asal" class="mb-1 block text-sm font-semibold">Negara Asal</label>
        <input id="negara_asal" name="negara_asal" value="{{ $field('negara_asal') }}" maxlength="100" placeholder="Contoh: Korea Selatan" class="{{ $cls('negara_asal') }}">
        @error('negara_asal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<p class="mt-4 text-xs text-slate-500">Satu merek dapat memiliki banyak produk. Nama merek harus unik.</p>

<div class="mt-6 flex justify-end gap-2">
    <a href="{{ route('admin.merek.index') }}" class="rounded-lg bg-slate-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-600">Batal</a>
    <button type="submit" class="rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2">Simpan</button>
</div>
