<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        return view('kategori.index', [
            'kategoris'     => Kategori::all()
        ]);
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', [
            'kategori'     => $kategori
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori'             => 'required'
        ], [
            'kategori.required'    => 'Form wajib diisi !'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Kategori::create([
            'kategori'     => $request->kategori
        ]);

        return redirect('/kategori')->with('success', 'Berhasil menambahkan data kategori');
    }

    public function update(Request $request, $id)
    {
        $tarif = Kategori::find($id);
        $validator = Validator::make($request->all(), 
        [
            'kategori'       => 'required',
        ], 
        [
            'kategori'       => 'Form wajib diisi !',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $tarif->update(
        [
            'kategori'        => $request->kategori,
        ]
    );

        return redirect('/kategori')->with('success', 'Berhasil memperbarui kategori');
    }

    public function destroy(Kategori $kategori)
    {
        // Periksa apakah ada pengguna yang masih menggunakan kategori ini
        if ($kategori->users->count() > 0) {
            return redirect()->back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh pelanggan.');
        }

        $kategori->delete();
        return redirect()->back()->with('success', 'Berhasil menghapus data kategori');
    }
}
