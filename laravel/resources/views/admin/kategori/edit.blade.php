@extends('layouts.admin')
@section('title', 'Ubah Kategori')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Ubah Kategori</h1>
    <p class="text-sm text-slate-500">Beranda › Data Master › Kategori › {{ $kategori->kode }}</p>
</div>

<form method="POST" action="{{ route('admin.kategori.update', $kategori) }}" class="max-w-xl rounded-xl border border-slate-200 bg-white p-5 shadow-sm" novalidate>
    @csrf
    @method('PUT')
    @include('admin.kategori._form')
</form>
@endsection