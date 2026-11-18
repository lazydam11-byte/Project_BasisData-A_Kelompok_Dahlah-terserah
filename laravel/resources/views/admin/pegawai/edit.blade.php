@extends('layouts.admin')
@section('title', 'Ubah Pegawai')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Ubah Pegawai</h1>
    <p class="text-sm text-slate-500">Beranda › Data Master › Pegawai › {{ $pegawai->nama }}</p>
</div>

<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <form method="POST" action="{{ route('admin.pegawai.update', $pegawai) }}">
        @csrf @method('PUT')
        @include('admin.pegawai._form')
    </form>
</section>
@endsection