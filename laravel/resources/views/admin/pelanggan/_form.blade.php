@php
    $ada = $pelanggan->exists;
    $input = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand';
@endphp

<div class="grid gap-4 p-4 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="nama" class="mb-1 block text-xs font-semibold">Nama lengkap</label>
        <input id="nama" name="nama" value="{{ old('nama', $pelanggan->nama) }}" required maxlength="100" class="{{ $input }}">
        @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="no_telepon" class="mb-1 block text-xs font-semibold">No. telepon</label>
        <input id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $pelanggan->no_telepon) }}" maxlength="20" inputmode="tel" class="{{ $input }}">
        @error('no_telepon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="email" class="mb-1 block text-xs font-semibold">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $pelanggan->email) }}" maxlength="100" class="{{ $input }}">
        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="alamat" class="mb-1 block text-xs font-semibold">Alamat</label>
        <textarea id="alamat" name="alamat" rows="3" maxlength="255" class="{{ $input }}">{{ old('alamat', $pelanggan->alamat) }}</textarea>
        @error('alamat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="username" class="mb-1 block text-xs font-semibold">Username</label>
        <input id="username" name="username" value="{{ old('username', $pelanggan->username) }}" required maxlength="50" autocomplete="off" class="{{ $input }}">
        <p class="mt-1 text-xs text-slate-500">Dipakai pelanggan untuk login. Tanpa spasi dan harus unik.</p>
        @error('username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password" class="mb-1 block text-xs font-semibold">{{ $ada ? 'Password baru' : 'Password' }}</label>
        <input id="password" type="password" name="password" autocomplete="new-password" @required(! $ada) minlength="6" class="{{ $input }}">
        <p class="mt-1 text-xs text-slate-500">{{ $ada ? 'Kosongkan jika password tidak diganti.' : 'Minimal 6 karakter.' }}</p>
        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="password_confirmation" class="mb-1 block text-xs font-semibold">Ulangi password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" @required(! $ada) class="{{ $input }}">
    </div>
</div>

<div class="flex items-center gap-3 border-t border-slate-200 p-4">
    <button class="rounded-lg bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark">{{ $ada ? 'Simpan perubahan' : 'Simpan pelanggan' }}</button>
    <a href="{{ route('admin.pelanggan.index') }}" class="text-sm text-slate-500 hover:underline">Batal</a>
</div>