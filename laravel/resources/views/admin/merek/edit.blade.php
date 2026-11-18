@extends('layouts.admin')
@section('title', 'Ubah Merek')

@section('content')
<div class="mb-4">
    <h1 class="font-display text-2xl font-bold">Ubah Merek</h1>
    <p class="text-sm text-slate-500">Beranda › Data Master › Merek › {{ $merek->kode }}</p>
</div>

<form method="POST" action="{{ route('admin.merek.update', $merek) }}" class="max-w-xl rounded-xl border border-slate-200 bg-white p-5 shadow-sm" novalidate>
    @csrf
    @method('PUT')
    @include('admin.merek._form')
</form>
@endsection
