<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TarifController extends Controller
{
    public function index()
    {
        return view('tarif.index', [
            'tarifs'     => Tarif::all()
        ]);
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('tarif.create',[
            'kategori' => $kategori,
        ]);
    }

    public function edit($id)
    {
        $tarif = Tarif::find($id);
        $kategori = Kategori::all();
        
        return view('tarif.edit', [
            'tarif' => $tarif,
            'kategori' => $kategori,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_id'       => 'required',
            'tarif_1' => 'required',
            'tarif_2' => 'required',
            'tarif_3' => 'required',
            'tarif_4' => 'required',
            'admin' => 'required',
            'denda' => 'required',
            'beban'         => 'required',
        ], [
            'kategori_id'       => 'Form wajib diisi !',
            'tarif_1' => 'Form wajib diisi !',
            'tarif_2' => 'Form wajib diisi !',
            'tarif_3' => 'Form wajib diisi !',
            'tarif_4' => 'Form wajib diisi !',
            'admin' => 'Form wajib diisi !',
            'denda'         => 'Form denda wajib diisi !',
            'beban'         => 'Form denda wajib diisi !'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Tarif::create([
            'kategori_id'        => $request->kategori_id,
            'tarif_1' => $request->tarif_1,
            'tarif_2' => $request->tarif_2,
            'tarif_3' => $request->tarif_3,
            'tarif_4' => $request->tarif_4,
            'admin' => $request->admin,
            'denda' => $request->denda,
            'beban'          => $request->beban,
        ]);

        return redirect('/tarif')->with('success', 'Berhasil menambahkan data tarif');
    }

    public function update(Request $request, $id)
    {
        $tarif = Tarif::find($id);
        $validator = Validator::make($request->all(), [
            'kategori_id'       => 'required',
            'tarif_1' => 'required',
            'tarif_2' => 'required',
            'tarif_3' => 'required',
            'tarif_4' => 'required',
            'admin' => 'required',
            'denda' => 'required',
            'beban'         => 'required',
        ], [
            'kategori_id'       => 'Form wajib diisi !',
            'tarif_1' => 'Form wajib diisi !',
            'tarif_2' => 'Form wajib diisi !',
            'tarif_3' => 'Form wajib diisi !',
            'tarif_4' => 'Form wajib diisi !',
            'admin' => 'Form wajib diisi !',
            'denda'         => 'Form denda wajib diisi !',
            'beban'         => 'Form denda wajib diisi !'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $tarif->update([
            'kategori_id'        => $request->kategori_id,
            'tarif_1' => $request->tarif_1,
            'tarif_2' => $request->tarif_2,
            'tarif_3' => $request->tarif_3,
            'tarif_4' => $request->tarif_4,
            'admin' => $request->admin,
            'denda' => $request->denda,
            'beban'          => $request->beban,
        ]);

        return redirect('/tarif')->with('success', 'Berhasil memperbarui tarif');
    }

    public function destroy(Tarif $tarif)
    {
        // Periksa apakah ada kategori yang masih menggunakan tarif ini
        if ($tarif->kategori) {
            return redirect()->back()->with('error', 'Tarif tidak dapat dihapus karena masih digunakan oleh kategori.');
        }
    
        // Jika tidak ada kategori yang menggunakan tarif ini, maka hapus tarif
        $tarif->delete();
        
        return redirect()->back()->with('success', 'Berhasil menghapus data tarif');
    }
}
