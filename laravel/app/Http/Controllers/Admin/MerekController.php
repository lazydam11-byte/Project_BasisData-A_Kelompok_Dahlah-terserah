<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Merek;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MerekController extends Controller
{
    public function index(Request $request)
    {
        $merek = Merek::withCount('produk')
            ->cari($request->input('q'))
            ->orderBy('id_merek')
            ->paginate(10)
            ->withQueryString();

        return view('admin.merek.index', compact('merek'));
    }

    public function create()
    {
        return view('admin.merek.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages(), $this->attributes());

        Merek::create($data);

        return redirect()->route('admin.merek.index')->with('success', 'Merek berhasil ditambahkan.');
    }

    public function edit(Merek $merek)
    {
        return view('admin.merek.edit', compact('merek'));
    }

    public function update(Request $request, Merek $merek)
    {
        $data = $request->validate($this->rules($merek), $this->messages(), $this->attributes());

        $merek->update($data);

        return redirect()->route('admin.merek.index')->with('success', 'Merek berhasil diperbarui.');
    }

    public function destroy(Merek $merek)
    {
        // FK produk.id_merek memakai restrictOnDelete, jadi cek dulu agar pesan error ramah.
        $jumlah = $merek->produk()->count();
        if ($jumlah > 0) {
            return redirect()->route('admin.merek.index')
                ->with('error', "Merek \"{$merek->nama_merek}\" tidak dapat dihapus karena masih dipakai oleh {$jumlah} produk.");
        }

        try {
            $merek->delete();
        } catch (QueryException $e) {
            return redirect()->route('admin.merek.index')
                ->with('error', 'Merek tidak dapat dihapus karena masih terhubung dengan data lain.');
        }

        return redirect()->route('admin.merek.index')->with('success', 'Merek berhasil dihapus.');
    }

    private function rules(?Merek $merek = null): array
    {
        return [
            'nama_merek' => [
                'required',
                'string',
                'max:100',
                Rule::unique('merek', 'nama_merek')->ignore($merek?->id_merek, 'id_merek'),
            ],
            'negara_asal' => ['required', 'string', 'max:100'],
        ];
    }

    private function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'max' => ':attribute tidak boleh melebihi :max karakter.',
            'unique' => ':attribute sudah terdaftar.',
        ];
    }

    private function attributes(): array
    {
        return [
            'nama_merek' => 'Nama merek',
            'negara_asal' => 'Negara asal',
        ];
    }
}
