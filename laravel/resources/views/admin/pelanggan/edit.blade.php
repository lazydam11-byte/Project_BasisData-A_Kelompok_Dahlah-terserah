@extends('layouts.admin')
@section('title', 'Ubah Pelanggan')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Ubah Pelanggan</h1>
    <p class="text-sm text-slate-500">Beranda › Data Master › Pelanggan › {{ $pelanggan->nama }}</p>
</div>

<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <form method="POST" action="{{ route('admin.pelanggan.update', $pelanggan) }}">
        @csrf @method('PUT')
        @include('admin.pelanggan._form')
    </form>
</section>
@endsection