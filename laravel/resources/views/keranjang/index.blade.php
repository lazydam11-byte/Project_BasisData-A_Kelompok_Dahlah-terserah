@extends('layouts.katalog')
@section('title', 'Keranjang')

@section('content')
<h1 class="mb-4 font-display text-2xl font-bold">Keranjang</h1>

@if ($baris->isEmpty())
    <div class="rounded-xl border border-slate-200 bg-white p-10 text-center shadow-sm">
        <p class="text-slate-600">Keranjang Anda masih kosong.</p>
        <a href="{{ route('katalog.index') }}" class="mt-4 inline-block rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">Lihat produk</a>
    </div>
@else
    <div class="grid gap-4 lg:grid-cols-3">
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
            <ul class="divide-y divide-slate-100">
                @foreach ($baris as $b)
                    @php $p = $b['produk']; @endphp
                    <li class="flex flex-wrap items-center gap-4 p-4">
                        @if ($p->foto_url)
                            <img src="{{ $p->foto_url }}" alt="" class="h-16 w-16 rounded object-cover">
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded bg-slate-100 text-[10px] text-slate-400">–</div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <p class="font-semibold">{{ $p->nama_produk }}</p>
                            <p class="text-xs text-slate-500">{{ $p->merek->nama_merek }} · stok {{ $p->stok }}</p>
                            <p class="text-sm">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                        </div>

                        <form method="POST" action="{{ route('keranjang.ubah', $p) }}" class="flex items-center gap-2">
                            @csrf @method('PUT')
                            <label for="jumlah-{{ $p->getKey() }}" class="sr-only">Jumlah {{ $p->nama_produk }}</label>
                            <input id="jumlah-{{ $p->getKey() }}" type="number" name="jumlah" value="{{ $b['jumlah'] }}" min="1" max="{{ max($p->stok, 1) }}" class="w-16 rounded-lg border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                            <button class="rounded bg-slate-100 px-3 py-1.5 text-xs font-semibold hover:bg-slate-200">Ubah</button>
                        </form>

                        <p class="w-28 text-right font-semibold">Rp {{ number_format($b['subtotal'], 0, ',', '.') }}</p>

                        <form method="POST" action="{{ route('keranjang.hapus', $p) }}">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-600 hover:underline">Hapus</button>
                        </form>
                    </li>
                @endforeach
            </ul>
            @error('jumlah') <p class="border-t border-slate-100 px-4 py-2 text-xs text-red-600">{{ $message }}</p> @enderror
        </section>

        <aside class="h-fit rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <h2 class="mb-3 font-display font-bold">Ringkasan pesanan</h2>
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 text-sm">
                <span class="text-slate-600">Total bayar</span>
                <span class="font-display text-xl font-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            @auth('pelanggan')
                <form method="POST" action="{{ route('pesanan.store') }}" class="mt-4">
                    @csrf

                    <h3 class="mb-2 text-xs font-semibold text-slate-600">Alamat pengiriman</h3>
                    <div class="mb-4 space-y-3">
                        <div>
                            <label for="nama_penerima" class="mb-1 block text-xs font-semibold">Nama penerima</label>
                            <input id="nama_penerima" name="nama_penerima" value="{{ old('nama_penerima', auth('pelanggan')->user()->nama) }}" required maxlength="100" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                            @error('nama_penerima') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="telepon_penerima" class="mb-1 block text-xs font-semibold">No. telepon penerima</label>
                            <input id="telepon_penerima" name="telepon_penerima" value="{{ old('telepon_penerima', auth('pelanggan')->user()->no_telepon) }}" required maxlength="20" inputmode="tel" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                            @error('telepon_penerima') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="alamat_pengiriman" class="mb-1 block text-xs font-semibold">Alamat lengkap</label>
                            <textarea id="alamat_pengiriman" name="alamat_pengiriman" rows="3" required maxlength="500" placeholder="Jalan, nomor rumah, kelurahan, kecamatan, kota, kode pos" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">{{ old('alamat_pengiriman', auth('pelanggan')->user()->alamat) }}</textarea>
                            @error('alamat_pengiriman') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <label for="metode_pembayaran" class="mb-1 block text-xs font-semibold">Metode pembayaran</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                        @foreach ($metode as $m)
                            <option value="{{ $m }}" @selected(old('metode_pembayaran') === $m)>{{ $m }}</option>
                        @endforeach
                    </select>
                    @error('metode_pembayaran') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                    <button class="mt-4 w-full rounded-lg bg-aksen px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-brand">Buat pesanan</button>
                    <p class="mt-3 text-xs text-slate-500">Pesanan dikirim ke alamat di atas setelah pembayaran dikonfirmasi pegawai. Estimasi tiba diberikan oleh pegawai.</p>
                </form>
            @else
                <p class="mt-4 text-sm text-slate-600">Masuk dulu untuk membuat pesanan. Isi keranjang Anda tetap tersimpan.</p>
                <a href="{{ route('login') }}" class="mt-3 block rounded-lg bg-brand px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-brand-dark">Masuk untuk memesan</a>
            @endauth
        </aside>
    </div>
@endif
@endsection