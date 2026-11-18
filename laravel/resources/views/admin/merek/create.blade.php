@extends('layouts.admin')
@section('title', 'Tambah Merek')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Tambah Merek</h1>
    <p class="text-sm text-slate-500">Beranda › Data Master › Merek › Tambah</p>
</div>

<form method="POST" action="{{ route('admin.merek.store') }}" class="max-w-xl rounded-xl border border-slate-200 bg-white p-5 shadow-sm" novalidate>
    @csrf
    @include('admin.merek._form')
</form>
@endsection
