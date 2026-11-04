<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Merek;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $produk = Produk::with(['kategori', 'merek'])
            ->cari($request->input('q'))
            ->when($request->filled('id_kategori'), fn ($q) => $q->where('id_kategori', $request->input('id_kategori')))
            ->orderBy('id_produk')
            ->paginate(10)
            ->withQueryString();

        return view('admin.produk.index', [
            'produk' => $produk,
            'daftarKategori' => Kategori::orderBy('nama_kategori')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.produk.create', $this->opsiForm());
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages(), $this->attributes());

        if ($request->hasFile('foto_produk')) {
            $data['foto_produk'] = $request->file('foto_produk')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        return view('admin.produk.edit', $this->opsiForm() + ['produk' => $produk]);
    }

    public function update(Request $request, Produk $produk)
    {
        $data = $request->validate($this->rules(), $this->messages(), $this->attributes());

        if ($request->hasFile('foto_produk')) {
            if ($produk->foto_produk) {
                Storage::disk('public')->delete($produk->foto_produk);
            }
            $data['foto_produk'] = $request->file('foto_produk')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->foto_produk) {
            Storage::disk('public')->delete($produk->foto_produk);
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function opsiForm(): array
    {
        return [
            'daftarKategori' => Kategori::orderBy('nama_kategori')->get(),
            'daftarMerek' => Merek::orderBy('nama_merek')->get(),
        ];
    }

    private function rules(): array
    {
        return [
            'nama_produk' => ['required', 'string', 'max:150'],
            'id_kategori' => ['required', 'exists:kategori,id_kategori'],
            'id_merek' => ['required', 'exists:merek,id_merek'],
            'harga' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'lama_garansi' => ['required', 'integer', 'min:0', 'max:120'],
            'tanggal_masuk' => ['required', 'date'],
            'foto_produk' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    private function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'integer' => ':attribute harus berupa angka bulat.',
            'min' => ':attribute tidak boleh kurang dari :min.',
            'max' => ':attribute tidak boleh melebihi :max.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'date' => ':attribute bukan tanggal yang valid.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat JPG atau PNG.',
            'foto_produk.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }

    private function attributes(): array
    {
        return [
            'nama_produk' => 'Nama produk',
            'id_kategori' => 'Kategori',
            'id_merek' => 'Merek',
            'harga' => 'Harga',
            'stok' => 'Stok',
            'lama_garansi' => 'Lama garansi',
            'tanggal_masuk' => 'Tanggal masuk',
            'foto_produk' => 'Foto produk',
        ];
    }
}
