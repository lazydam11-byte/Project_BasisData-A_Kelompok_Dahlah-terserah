<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $pelanggan = Pelanggan::query()
            ->when($request->q, function ($query, $q) {
                $query->where(function ($w) use ($q) {
                    $w->where('nama', 'like', "%{$q}%")
                      ->orWhere('username', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('no_telepon', 'like', "%{$q}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('admin.pelanggan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        Pelanggan::create($data); // password di-hash otomatis oleh cast 'hashed'

        return redirect()->route('admin.pelanggan.index')
            ->with('sukses', 'Pelanggan ' . $data['nama'] . ' berhasil ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $data = $request->validate($this->rules($pelanggan));

        // Password dikosongkan = tidak diganti
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $pelanggan->update($data);

        return redirect()->route('admin.pelanggan.index')
            ->with('sukses', 'Data pelanggan ' . $pelanggan->nama . ' berhasil diperbarui.');
    }

    private function rules(?Pelanggan $pelanggan = null): array
    {
        $id = $pelanggan?->getKey();

        return [
            'nama'       => ['required', 'string', 'max:100'],
            'alamat'     => ['nullable', 'string', 'max:255'],
            'no_telepon' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'email'      => ['nullable', 'email', 'max:100', Rule::unique('pelanggan', 'email')->ignore($id, 'id_pelanggan')],
            'username'   => ['required', 'string', 'max:50', 'regex:/^\S+$/', Rule::unique('pelanggan', 'username')->ignore($id, 'id_pelanggan')],
            'password'   => $pelanggan
                ? ['nullable', 'string', 'min:6', 'confirmed']
                : ['required', 'string', 'min:6', 'confirmed'],
        ];
    }
}