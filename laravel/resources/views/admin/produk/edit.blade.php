@extends('layouts.admin')
@section('title', 'Ubah Produk')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Ubah Produk</h1>
    <p class="text-sm text-slate-500">Beranda › Data Master › Produk › {{ $produk->kode }}</p>
</div>

<form method="POST" action="{{ route('admin.produk.update', $produk) }}" enctype="multipart/form-data" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm" novalidate>
    @csrf
    @method('PUT')
    @include('admin.produk._form')
</form>
@endsection
