<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\periode;
use App\Models\Tarif;
use App\Models\Pemakaian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\WaService;
use Illuminate\Support\Facades\Validator;

class PemakaianController extends Controller
{
    protected $waService;

    public function __construct(WaService $waService)
    {
        $this->waService = $waService;
    }

    public function index()
    {
        return view('catat-pemakaian.index', [
            'users'        => User::where('role_id', '3')->get(),
            'periodes'     => Periode::where('status', 'Aktif')->get(),
        ]);
    }

    public function getData($user_id)
    {
        $dataPenggunaan = Pemakaian::where('user_id', $user_id)
            ->latest('created_at')
            ->first();
        return response()->json($dataPenggunaan);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'penggunaan_awal'   => 'required',
            'penggunaan_akhir'  => 'required',
            'jumlah_penggunaan' => 'required',
            'user_id'           => 'required',
            'periode_id'        => 'required',
            'foto_meteran'      => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'penggunaan_awal.required'  => 'Form wajib diisi !',
            'penggunaan_akhir.required' => 'Form wajib diisi !',
            'jumlah_penggunaan.required' => 'Form wajib diisi !',
            'user_id.required'          => 'Form wajib diisi !',
            'periode_id.required'       => 'Form wajib diisi !',
            'foto_meteran'      => 'Form wajib diisi !',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('foto_meteran');
        $filename = $request->file('foto_meteran')->getClientOriginalName();
        $file->move(public_path('catat-meter'), $filename);
        
        $existingEntry = Pemakaian::where('periode_id', $request->periode_id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existingEntry) {
            return back()->with('error', 'Pemakaian sudah dicatat untuk pengguna ini dalam periode yang sama.');
        }

        $user = User::find($request->user_id);

        $kategori_id = $user->kategori_id;
        
        $tarif = Tarif::where('kategori_id', $kategori_id)->first();
    
        $jumlah_penggunaan  = $request->jumlah_penggunaan;
        $beban              = $tarif->beban;
        $admin              = $tarif->admin;

        if ($jumlah_penggunaan <= 10) {
            $total_tarif = $tarif->tarif_1 * $jumlah_penggunaan;
            // Lakukan sesuatu dengan $total_tarif
        } elseif ($jumlah_penggunaan <= 20) {
            $total_tarif = $tarif->tarif_2 * $jumlah_penggunaan;
            // Lakukan sesuatu dengan $total_tarif
        } elseif ($jumlah_penggunaan <= 30) {
            $total_tarif = $tarif->tarif_3 * $jumlah_penggunaan;
            // Lakukan sesuatu dengan $total_tarif
        } else {
            $total_tarif = $tarif->tarif_4 * $jumlah_penggunaan;
            // Lakukan sesuatu dengan $total_tarif
        }
        
        
        $jumlah_pembayaran  = $total_tarif + $beban + $admin;

        $foto_meteran = $request->file('foto_meteran')->getClientOriginalName();

        $data = $request->all();
        $data['foto_meteran']  = $foto_meteran;
        
        $data['jumlah_pembayaran']  = $jumlah_pembayaran;

        $waResponse = $this->waService->sendWa($data);
        
        // Periksa status respon
        $response = json_decode($waResponse, true);
        // dd($response);

        if (isset($response['status']) && $response['status'] === false) {
            // Jika terjadi kesalahan dalam pengiriman pesan WhatsApp
            Pemakaian::create($data);
            return redirect()->back()->with('error', 'Data pemakaian berhasil disimpan! Gagal mengirim pesan WhatsApp: ' . $response['message']);
        } else {
            // Jika pengiriman berhasil, lanjutkan dengan operasi lainnya
            Pemakaian::create($data);
            return redirect()->back()->with('success', 'Data pemakaian berhasil disimpan! Pesan WhatsApp berhasil dikirim!');
        }
        

        return redirect()->back()->with('success', 'Data pemakaian berhasil di simpan !');
    }

    /**
     * Get Data Pelanggan From Scanner Qr Code
     */
    public function getDataPelanggan(Request $request)
    {
        $qrCode     = $request->input('result');
        $pelanggan  = User::where('no_pelanggan', $qrCode)->first();

        $dataPelanggan = [
            'id'                => null,
            'user_id'           => null,
            'penggunaan_akhir'  => null
        ];

        if ($pelanggan) {
            $pemakaian = Pemakaian::where('user_id', $pelanggan->id)
                ->orderBy('created_at', 'desc')
                ->first();
            $penggunaan_akhir = ($pemakaian) ? $pemakaian->penggunaan_akhir : 0;
            $dataPelanggan = [
                'id'                  => $pelanggan->id,
                'user_id'             => $pelanggan->id,
                'penggunaan_akhir'    => $penggunaan_akhir
            ];
        }

        return response()->json($dataPelanggan);
    }
}
