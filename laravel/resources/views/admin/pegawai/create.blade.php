@extends('layouts.admin')
@section('title', 'Tambah Pegawai')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Tambah Pegawai</h1>
    <p class="text-sm text-slate-500">Beranda › Data Master › Pegawai › Tambah</p>
</div>

<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <form method="POST" action="{{ route('admin.pegawai.store') }}">
        @csrf
        @include('admin.pegawai._form', ['pegawai' => new \App\Models\Pegawai])
    </form>
</section>
@endsection