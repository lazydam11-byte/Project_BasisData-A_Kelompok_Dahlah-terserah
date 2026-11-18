@php
    $kelas = match ($klaim->status_klaim) {
        'Selesai'  => 'bg-green-100 text-green-800',
        'Ditolak'  => 'bg-red-100 text-red-700',
        'Diproses' => 'bg-blue-100 text-blue-800',
        default    => 'bg-amber-100 text-amber-800',
    };
@endphp
<span class="rounded px-2 py-0.5 text-xs font-semibold {{ $kelas }}">{{ $klaim->status_klaim }}</span>