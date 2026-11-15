@extends('layouts.admin')
@section('title', 'Tambah Pelanggan')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Tambah Pelanggan</h1>
    <p class="text-sm text-slate-500">Beranda › Data Master › Pelanggan › Tambah</p>
</div>

<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <form method="POST" action="{{ route('admin.pelanggan.store') }}">
        @csrf
        @include('admin.pelanggan._form', ['pelanggan' => new \App\Models\Pelanggan])
    </form>
</section>
@endsection