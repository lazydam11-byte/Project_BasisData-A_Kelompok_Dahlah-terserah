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
            <div class="space-y-2 border-b border-slate-100 pb-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-slate-600">Subtotal produk</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div id="baris-ongkir" style="display:none">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">Ongkos kirim</span>
                        <span id="ringkas-ongkir">Rp 0</span>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-1">
                    <span class="text-slate-600">Total bayar</span>
                    <span id="ringkas-total" class="font-display text-xl font-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            @auth('pelanggan')
                <form method="POST" action="{{ route('pesanan.store') }}" class="mt-4">
                    @csrf
                    @php $cara = old('penerimaan', 'Ambil di Toko'); $input = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand'; @endphp
                    <fieldset>
                        <legend class="mb-1 block text-xs font-semibold">Cara menerima pesanan</legend>
                        <label class="mb-1 flex items-center gap-2 text-sm">
                            <input type="radio" name="penerimaan" value="Ambil di Toko" @checked($cara === 'Ambil di Toko')> Ambil di toko
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="radio" name="penerimaan" value="Kirim ke Alamat" @checked($cara === 'Kirim ke Alamat')> Kirim ke alamat saya
                        </label>
                        @error('penerimaan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </fieldset>

                    <div id="form-kirim" class="mt-3 space-y-3 {{ $cara === 'Kirim ke Alamat' ? '' : 'hidden' }}">
                        <div>
                            <label for="nama_penerima" class="mb-1 block text-xs font-semibold">Nama penerima</label>
                            <input id="nama_penerima" type="text" name="nama_penerima" maxlength="100" value="{{ old('nama_penerima', $pelanggan->nama ?? '') }}" class="{{ $input }}">
                            @error('nama_penerima') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="telepon_penerima" class="mb-1 block text-xs font-semibold">Telepon penerima</label>
                            <input id="telepon_penerima" type="tel" name="telepon_penerima" maxlength="20" value="{{ old('telepon_penerima', $pelanggan->no_telepon ?? '') }}" class="{{ $input }}">
                            @error('telepon_penerima') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="alamat_tujuan" class="mb-1 block text-xs font-semibold">Alamat lengkap</label>
                            <textarea id="alamat_tujuan" name="alamat_tujuan" rows="3" maxlength="500" placeholder="Jalan, nomor, kelurahan, kecamatan, kota, kode pos" class="{{ $input }}">{{ old('alamat_tujuan', $pelanggan->alamat ?? '') }}</textarea>
                            @error('alamat_tujuan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="kurir" class="mb-1 block text-xs font-semibold">Kurir</label>
                            <select id="kurir" name="kurir" class="{{ $input }}">
                                <option value="" data-tarif="0">Pilih kurir</option>
                                @foreach ($kurir as $nama => $info)
                                    <option value="{{ $nama }}" data-tarif="{{ $info['tarif'] }}" @selected(old('kurir') === $nama)>{{ $nama }} (Rp {{ number_format($info['tarif'], 0, ',', '.') }}, {{ $info['estimasi'] }})</option>
                                @endforeach
                            </select>
                            @error('kurir') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <label for="metode_pembayaran" class="mb-1 mt-3 block text-xs font-semibold">Metode pembayaran</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand">
                        @foreach ($metode as $m)
                            <option value="{{ $m }}" @selected(old('metode_pembayaran') === $m)>{{ $m }}</option>
                        @endforeach
                    </select>
                    @error('metode_pembayaran') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                    <button class="mt-4 w-full rounded-lg bg-aksen px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-brand">Buat pesanan</button>
                    <p id="catatan-terima" class="mt-3 text-xs text-slate-500">Pesanan diambil di toko setelah pembayaran dikonfirmasi oleh pegawai.</p>

                    <script>
                        (function () {
                            var subtotal = {{ (int) $total }};
                            var box = document.getElementById('form-kirim');
                            var kurir = document.getElementById('kurir');
                            var metode = document.getElementById('metode_pembayaran');
                            var wajib = ['nama_penerima', 'telepon_penerima', 'alamat_tujuan', 'kurir'];
                            function rupiah(n) { return 'Rp ' + String(n).replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }
                            function perbarui() {
                                var kirim = document.querySelector('input[name=penerimaan]:checked').value === 'Kirim ke Alamat';
                                box.classList.toggle('hidden', !kirim);
                                wajib.forEach(function (id) { document.getElementById(id).required = kirim; });
                                var opsi = kurir.options[kurir.selectedIndex];
                                var ongkir = kirim && opsi ? parseInt(opsi.getAttribute('data-tarif') || '0', 10) : 0;
                                document.getElementById('baris-ongkir').style.display = kirim ? 'block' : 'none';
                                document.getElementById('ringkas-ongkir').textContent = rupiah(ongkir);
                                document.getElementById('ringkas-total').textContent = rupiah(subtotal + ongkir);
                                Array.prototype.forEach.call(metode.options, function (o) {
                                    if (o.value === 'Bayar di Toko') {
                                        o.disabled = kirim;
                                        if (kirim && o.selected) { metode.selectedIndex = 0; }
                                    }
                                });
                                document.getElementById('catatan-terima').textContent = kirim
                                    ? 'Pesanan dikemas dan dikirim setelah pembayaran dikonfirmasi pegawai. Pembayaran lewat Transfer Bank atau QRIS.'
                                    : 'Pesanan diambil di toko setelah pembayaran dikonfirmasi oleh pegawai.';
                            }
                            document.querySelectorAll('input[name=penerimaan]').forEach(function (r) { r.addEventListener('change', perbarui); });
                            kurir.addEventListener('change', perbarui);
                            perbarui();
                        })();
                    </script>
                </form>
            @else
                <p class="mt-4 text-sm text-slate-600">Masuk dulu untuk membuat pesanan. Isi keranjang Anda tetap tersimpan.</p>
                <a href="{{ route('login') }}" class="mt-3 block rounded-lg bg-brand px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-brand-dark">Masuk untuk memesan</a>
            @endauth
        </aside>
    </div>
@endif
@endsection