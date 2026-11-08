@extends('layouts.admin')
@section('title', 'Tambah Produk')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Tambah Produk</h1>
    <p class="text-sm text-slate-500">Beranda › Data Master › Produk › Tambah</p>
</div>

<form method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm" novalidate>
    @csrf
    @include('admin.produk._form')
</form>
@endsection
