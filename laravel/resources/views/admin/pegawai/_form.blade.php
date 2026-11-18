@php
    $adaPegawai = $pegawai->exists;
    $input = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand';
@endphp

<div class="grid gap-4 p-4 md:grid-cols-2">
    <div>
        <label for="nama" class="mb-1 block text-xs font-semibold">Nama lengkap</label>
        <input id="nama" name="nama" value="{{ old('nama', $pegawai->nama) }}" required maxlength="100" class="{{ $input }}">
        @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="jabatan" class="mb-1 block text-xs font-semibold">Jabatan</label>
        <select id="jabatan" name="jabatan" required class="{{ $input }}">
            <option value="">Pilih jabatan</option>
            @foreach ($daftarJabatan as $j)
                <option value="{{ $j }}" @selected(old('jabatan', $pegawai->jabatan) === $j)>{{ $j }}</option>
            @endforeach
        </select>
        @error('jabatan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="username" class="mb-1 block text-xs font-semibold">Username</label>
        <input id="username" name="username" value="{{ old('username', $pegawai->username) }}" required maxlength="50" autocomplete="off" class="{{ $input }}">
        <p class="mt-1 text-xs text-slate-500">Dipakai untuk login. Tanpa spasi dan tidak boleh sama dengan pegawai lain.</p>
        @error('username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="mb-1 block text-xs font-semibold">{{ $adaPegawai ? 'Password baru' : 'Password' }}</label>
        <input id="password" type="password" name="password" autocomplete="new-password" @required(! $adaPegawai) minlength="6" class="{{ $input }}">
        <p class="mt-1 text-xs text-slate-500">
            {{ $adaPegawai ? 'Kosongkan jika password tidak diganti.' : 'Minimal 6 karakter.' }}
        </p>
        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password_confirmation" class="mb-1 block text-xs font-semibold">Ulangi password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" @required(! $adaPegawai) class="{{ $input }}">
    </div>
</div>

<div class="flex items-center gap-3 border-t border-slate-200 p-4">
    <button class="rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">{{ $adaPegawai ? 'Simpan perubahan' : 'Simpan pegawai' }}</button>
    <a href="{{ route('admin.pegawai.index') }}" class="text-sm text-slate-500 hover:underline">Batal</a>
</div>