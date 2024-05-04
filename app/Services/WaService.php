<?php

namespace App\Services;

use App\Models\Periode;
use App\Models\User;
use Carbon\Carbon;

class WaService
{
    public function sendWa($data)
    {
        
        $user = User::find($data['user_id']);
        $userName = $user['name'];
        $userPhone = $user['no_hp'];
        // Periksa apakah nomor telepon dimulai dengan '62'
        if (substr($userPhone, 0, 2) !== '62') {
            // Jika tidak, tambahkan '62' di depan nomor telepon
            $userPhone = '62' . substr($userPhone, 1);
        }

        $periode = Periode::find($data['periode_id']);
        $periodeName = $periode['periode'];
        $batasBayar = Carbon::parse($data['batas_bayar'])->locale('id')->isoFormat('LL');

        // Angka yang akan diformat
        $jumlahPembayaran = $data['jumlah_pembayaran'];

        // Memformat angka menjadi format mata uang Indonesia (Rupiah)
        $jumlahPembayaranFormatted = 'Rp. ' . number_format($jumlahPembayaran, 0, ',', '.');


        $pesan = "Assalamualaikum " . $userName .", tagihan anda pada " . $periodeName . " adalah sejumlah " . $jumlahPembayaranFormatted . ". Silahkan melakukan pembayaran melalui aplikasi atau melalui outlet terdekat sebelum tanggal " . $batasBayar;
        $api = settings()->get('wa_api');
        
        $body = array(
            "api_key" => env('WA_API'),
            "receiver" => $userPhone,
            "data" => array("message" => $pesan)
          );
          
          $curl = curl_init();
          curl_setopt_array($curl, [
            CURLOPT_URL => "https://wa.bumdespringgondani.com/api/send-message",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
              "Accept: */*",
              "Content-Type: application/json",
            ],
          ]);
          
          $response = curl_exec($curl);
          $err = curl_error($curl);
          
          curl_close($curl);
          
          if ($err) {
            return "cURL Error #:" . $err;
          } else {
            return $response;
          }
    }
}