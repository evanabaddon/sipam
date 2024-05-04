<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use Illuminate\Http\Request;
use DateTime;

class TripayController extends Controller
{
    public function getChannel(){
        $apiKey = env('TRIPAY_API_KEY');

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_FRESH_CONNECT  => true,
        CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/payment/channel',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
        CURLOPT_FAILONERROR    => false,
        CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
        CURLOPT_SSL_VERIFYPEER => false,
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        return $response ?: $error;
    }

    public function requestTransantion($paymentCode, $tagihan) {

        $kategoriId = $tagihan->user->kategori_id;
        $tarif = Tarif::where('kategori_id', $kategoriId)->first();
        
        
        $jumlah_pembayaran = $tagihan->jumlah_pembayaran;
        $kd_pembayaran  = 'INV-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $tgl_bayar      = now();
        $denda          = 0;

        $selisihBulan    = $this->calculateMonthDifference($tgl_bayar, $tagihan->batas_bayar);

        $dendaPerBulan   = $tarif->denda;
        $denda           = $selisihBulan * $dendaPerBulan;
        
        $amount = $jumlah_pembayaran + $denda;

        $apiKey       = env('TRIPAY_API_KEY');
        $privateKey   = env('TRIPAY_PRIVATE_KEY');
        $merchantCode = env('TRIPAY_MERCHANT_CODE');
        $merchantRef  = $kd_pembayaran;
        $amount       = $amount;

        $data = [
            'method'         => $paymentCode,
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $tagihan->user->name,
            'customer_email' => $tagihan->user->email,
            'customer_phone' => $tagihan->user->no_hp,
            'order_items'    => [
                [
                    'sku'         => $kd_pembayaran,
                    'name'        => 'Pembayaran '.$tagihan->periode->periode,
                    'price'       => $amount,
                    'quantity'    => 1,
                ],
            ],
            'return_url'   => 'https://domainanda.com/redirect',
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => hash_hmac('sha256', $merchantCode.$merchantRef.$amount, $privateKey)
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/transaction/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        return $response ?: $error;
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
