<?php

namespace App\Http\Controllers;

use DateTime;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Tarif;
use App\Models\Pemakaian;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CekTagihanPelangganController extends Controller
{
    public function index()
    {
        $user = auth()->user()->id;
        return view('cek-tagihan.index', [
            'tagihans'  => Pemakaian::with(['periode', 'user'])->where('user_id', $user)
                ->where('status', 'belum dibayar')
                ->orderBy('id', 'DESC')
                ->get()
        ]);
    }

    public function detailTagihan($id)
    {
        $tagihan = Pemakaian::with(['periode', 'user'])->find($id);
        $kategoriId = $tagihan->user->kategori_id;
        $tarif = Tarif::where('kategori_id', $kategoriId)->first();

        // Tentukan tarif berdasarkan jumlah pemakaian
        $jumlahPemakaian = $tagihan->jumlah_penggunaan;

        if ($jumlahPemakaian <= 10) {
            $tarifPemakaian = $tarif->tarif_1;
        } elseif ($jumlahPemakaian <= 20) {
            $tarifPemakaian = $tarif->tarif_2;
        } elseif ($jumlahPemakaian <= 30) {
            $tarifPemakaian = $tarif->tarif_3;
        } else {
            $tarifPemakaian = $tarif->tarif_4;
        }

        $tripay = new TripayController();

        $channel = $tripay->getChannel();

        $channelPembayaran = json_decode($channel)->data;

        // dd($channelPembayaran);

        return view('cek-tagihan.detail', [
            'tagihan' => $tagihan,
            'tarif' => $tarif,
            'tarifPemakaian' => $tarifPemakaian,
            'channels' => $channelPembayaran
        ]);
    }

    public function prosesBayar(Request $request)
    {
        try {
            $paymentCode = $request->input('payment_code');
            $tagihanId = $request->input('pemakaian_id');

            // Memuat data tagihan beserta relasinya
            $tagihan = Pemakaian::with(['periode', 'user'])->find($tagihanId);

            // Pastikan objek $tagihan ada dan memiliki relasi 'user'
            if ($tagihan && $tagihan->user) {
                $tripay = new TripayController();
                $response = $tripay->requestTransantion($paymentCode, $tagihan);

                // Mengubah response menjadi array
                $responseData = json_decode($response, true);

                // Tambahkan atribut 'success' ke dalam respons
                $responseData['success'] = true;

                session()->put('payment_data', $responseData['data']);

                // Kembalikan respons sebagai JSON
                return response()->json($responseData);
            } else {
                // Jika tagihan tidak ditemukan atau tidak memiliki relasi 'user'
                return response()->json(['success' => false, 'error' => 'Tagihan tidak ditemukan atau tidak valid.']);
            }
            
        } catch (\Exception $e) {
            // Tangani kesalahan yang mungkin terjadi
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }




    public function prosesPembayaran(Request $request)
    {;
        // Mendapatkan data respons dari sesi
        $data = session()->get('data');

        // Mengembalikan tampilan halaman detail pembayaran dengan membawa data respons
        return view('cek-tagihan.proses', compact('data'));
    }

    public function bayar(Request $request)
    {
        $pemakaian_id = $request->input('pemakaian_id');
        $subTotal = $request->input('jumlah_pembayaran');

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is3Ds');

        $params = array(
            'transaction_details' => array(
                'order_id' => $pemakaian_id . '_' . time(),
                'gross_amount' => $subTotal,
            ),
            'customer_details' => array(
                'first_name' => auth()->user()->name,
                'phone' => auth()->user()->no_hp,
            ),
            'ignore_duplicate_order_id' => true,
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);
                
        return response()->json(['snapToken' => $snapToken]);
    }

    public function callback(Request $request)
    {
        $kd_pembayaran  = 'INV-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $kembalian      = 0;
        $tgl_bayar      = now();
        $denda          = 0;
        $tarif          = Tarif::first();

        $serverKey = config('midtrans.server_key');
        $hashed    = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $denda . $serverKey);

        if ($request->transaction_status == 'capture' or $request->transaction_status == 'settlement') {
            $order_id_with_timestamp = $request->order_id;
            [$order_id, $timestamp] = explode('_', $order_id_with_timestamp);
            $gross_amount    = $request->gross_amount;

            $pemakaian = Pemakaian::where('id', $order_id)->firstOrFail();

            $pemakaian->update(['status' => 'lunas']);

            $tanggal_batas_bayar = new DateTime($pemakaian->batas_bayar);
            $tgl_bayar = new DateTime();

            if ($tgl_bayar > $tanggal_batas_bayar) {
                $selisihBulan    = $this->calculateMonthDifference($tgl_bayar, $tanggal_batas_bayar);
                $dendaPerBulan   = $tarif->denda;
                $denda           = $selisihBulan * $dendaPerBulan;
            }

            $pembayaran     = new Pembayaran();
            $pembayaran->pemakaian_id    = $pemakaian->id;
            $pembayaran->kd_pembayaran   = $kd_pembayaran;
            $pembayaran->tgl_bayar       = $tgl_bayar->format('Y-m-d');
            $pembayaran->uang_cash       = $gross_amount;
            $pembayaran->kembalian       = $kembalian;
            $pembayaran->denda           = $denda;
            $pembayaran->subTotal        = $gross_amount;
            $pembayaran->save();
        }
    }


    private function calculateMonthDifference($date1, $date2)
    {
        if ($date1 instanceof DateTime) {
            $start = $date1;
        } else {
            $start = new DateTime($date1);
        }

        if ($date2 instanceof DateTime) {
            $end = $date2;
        } else {
            $end = new DateTime($date2);
        }

        $interval = $start->diff($end);
        $years = $interval->y;
        $months = $interval->m;

        if ($interval->d > 0) {
            $months += 1;
        }

        return $years * 12 + $months;
    }
}
