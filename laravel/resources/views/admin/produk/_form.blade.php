@php
    $field = fn ($name, $default = '') => old($name, isset($produk) ? ($produk->{$name} ?? $default) : $default);
    $cls = fn ($name) => 'w-full rounded-lg border px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand ' . ($errors->has($name) ? 'border-red-400' : 'border-slate-300');
@endphp

@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
        Data belum bisa disimpan. Periksa kolom yang ditandai merah.
    </div>
@endif

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <div class="sm:col-span-2 lg:col-span-3">
        <label for="nama_produk" class="mb-1 block text-sm font-semibold">Nama Produk</label>
        <input id="nama_produk" name="nama_produk" value="{{ $field('nama_produk') }}" placeholder="Masukkan nama produk…" class="{{ $cls('nama_produk') }}">
        @error('nama_produk') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="id_kategori" class="mb-1 block text-sm font-semibold">Kategori</label>
        <select id="id_kategori" name="id_kategori" class="{{ $cls('id_kategori') }}">
            <option value="">Pilih kategori</option>
            @foreach ($daftarKategori as $k)
                <option value="{{ $k->id_kategori }}" @selected($field('id_kategori') == $k->id_kategori)>{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
        @error('id_kategori') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="id_merek" class="mb-1 block text-sm font-semibold">Merek</label>
        <select id="id_merek" name="id_merek" class="{{ $cls('id_merek') }}">
            <option value="">Pilih merek</option>
            @foreach ($daftarMerek as $m)
                <option value="{{ $m->id_merek }}" @selected($field('id_merek') == $m->id_merek)>{{ $m->nama_merek }}</option>
            @endforeach
        </select>
        @error('id_merek') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="harga" class="mb-1 block text-sm font-semibold">Harga (Rp)</label>
        <input id="harga" name="harga" type="number" min="0" value="{{ $field('harga') }}" placeholder="cth. 18500000" class="{{ $cls('harga') }}">
        @error('harga') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="stok" class="mb-1 block text-sm font-semibold">Stok</label>
        <input id="stok" name="stok" type="number" min="0" value="{{ $field('stok') }}" placeholder="cth. 10" class="{{ $cls('stok') }}">
        @error('stok') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="lama_garansi" class="mb-1 block text-sm font-semibold">Lama Garansi (bulan)</label>
        <input id="lama_garansi" name="lama_garansi" type="number" min="0" value="{{ $field('lama_garansi', 0) }}" placeholder="cth. 12 (0 = tanpa garansi)" class="{{ $cls('lama_garansi') }}">
        @error('lama_garansi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="tanggal_masuk" class="mb-1 block text-sm font-semibold">Tanggal Masuk</label>
        <input id="tanggal_masuk" name="tanggal_masuk" type="date"
               value="{{ old('tanggal_masuk', isset($produk) ? $produk->tanggal_masuk->format('Y-m-d') : now()->format('Y-m-d')) }}"
               class="{{ $cls('tanggal_masuk') }}">
        @error('tanggal_masuk') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label for="foto_produk" class="mb-1 block text-sm font-semibold">Foto Produk</label>
        <input id="foto_produk" name="foto_produk" type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png"
               class="{{ $cls('foto_produk') }} file:mr-3 file:rounded file:border-0 file:bg-brand-soft file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-brand-dark">
        <p class="mt-1 text-xs text-slate-500">JPG atau PNG, maksimal 2 MB.@isset($produk) @if ($produk->foto_produk) Kosongkan jika tidak ingin mengganti foto.@endif @endisset</p>
        @error('foto_produk') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        @isset($produk)
            @if ($produk->foto_url)
                <img src="{{ $produk->foto_url }}" alt="Foto {{ $produk->nama_produk }}" class="mt-2 h-24 w-24 rounded-lg object-cover">
            @endif
        @endisset
    </div>
</div>

<p class="mt-4 text-xs text-slate-500">Produk terhubung ke satu kategori dan satu merek. Harga dalam Rupiah. Lama garansi dalam bulan (0 = tanpa garansi).</p>

<div class="mt-6 flex justify-end gap-2">
    <a href="{{ route('admin.produk.index') }}" class="rounded-lg bg-slate-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-600">Batal</a>
    <button type="submit" class="rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2">Simpan</button>
</div>
