<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::withCount('produk')
            ->cari($request->input('q'))
            ->orderBy('id_kategori')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages(), $this->attributes());

        Kategori::create($data);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate($this->rules($kategori), $this->messages(), $this->attributes());

        $kategori->update($data);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        // FK produk.id_kategori memakai restrictOnDelete, jadi cek dulu agar pesan error ramah.
        if ($kategori->produk()->exists()) {
            return redirect()->route('admin.kategori.index')
                ->with('error', "Kategori \"{$kategori->nama_kategori}\" tidak dapat dihapus karena masih dipakai oleh {$kategori->produk()->count()} produk.");
        }

        try {
            $kategori->delete();
        } catch (QueryException $e) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih terhubung dengan data lain.');
        }

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }

    private function rules(?Kategori $kategori = null): array
    {
        return [
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori', 'nama_kategori')->ignore($kategori?->id_kategori, 'id_kategori'),
            ],
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
        return ['nama_kategori' => 'Nama kategori'];
    }
}
