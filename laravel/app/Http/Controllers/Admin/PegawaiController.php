<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $pegawai = Pegawai::query()
            ->when($request->q, function ($query, $q) {
                $query->where(function ($w) use ($q) {
                    $w->where('nama', 'like', "%{$q}%")
                      ->orWhere('username', 'like', "%{$q}%");
                });
            })
            ->when($request->jabatan, fn ($query, $j) => $query->where('jabatan', $j))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.pegawai.index', [
            'pegawai'      => $pegawai,
            'daftarJabatan' => Pegawai::JABATAN,
        ]);
    }

    public function create()
    {
        return view('admin.pegawai.create', ['daftarJabatan' => Pegawai::JABATAN]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        Pegawai::create($data); // password di-hash otomatis oleh cast 'hashed'

        return redirect()->route('admin.pegawai.index')
            ->with('sukses', 'Pegawai ' . $data['nama'] . ' berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        return view('admin.pegawai.edit', [
            'pegawai'       => $pegawai,
            'daftarJabatan' => Pegawai::JABATAN,
        ]);
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $data = $request->validate($this->rules($pegawai));

        // Password dikosongkan = tidak diganti
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Jangan sampai toko tidak punya Manajer sama sekali
        if ($pegawai->jabatan === 'Manajer' && $data['jabatan'] !== 'Manajer' && $this->hanyaSatuManajer()) {
            return back()->withInput()->withErrors([
                'jabatan' => 'Ini satu-satunya Manajer. Jadikan pegawai lain Manajer dulu sebelum mengubah jabatan ini.',
            ]);
        }

        $pegawai->update($data);

        return redirect()->route('admin.pegawai.index')
            ->with('sukses', 'Data pegawai ' . $pegawai->nama . ' berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        if ($pegawai->getKey() === auth('pegawai')->id()) {
            return back()->with('error', 'Akun yang sedang dipakai tidak bisa dihapus.');
        }

        if ($pegawai->jabatan === 'Manajer' && $this->hanyaSatuManajer()) {
            return back()->with('error', 'Manajer terakhir tidak bisa dihapus.');
        }

        try {
            $pegawai->delete();
        } catch (QueryException $e) {
            // Biasanya karena pegawai sudah tercatat di tabel lain (foreign key)
            return back()->with('error', 'Pegawai ' . $pegawai->nama . ' tidak bisa dihapus karena sudah punya data terkait.');
        }

        return redirect()->route('admin.pegawai.index')
            ->with('sukses', 'Pegawai ' . $pegawai->nama . ' berhasil dihapus.');
    }

    private function rules(?Pegawai $pegawai = null): array
    {
        return [
            'nama'     => ['required', 'string', 'max:100'],
            'jabatan'  => ['required', Rule::in(Pegawai::JABATAN)],
            'username' => [
                'required', 'string', 'max:50', 'regex:/^\S+$/',
                Rule::unique('pegawai', 'username')->ignore($pegawai?->getKey(), 'id_pegawai'),
            ],
            'password' => $pegawai
                ? ['nullable', 'string', 'min:6', 'confirmed']
                : ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    private function hanyaSatuManajer(): bool
    {
        return Pegawai::where('jabatan', 'Manajer')->count() <= 1;
    }
}