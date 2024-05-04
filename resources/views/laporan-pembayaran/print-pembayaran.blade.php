<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pembayaran</title>
    <style>
        .container {
            text-align: center;
            margin: auto;
        }

        .column {
            text-align: center;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        table {
            margin: auto;
            width: 100%;
        }

        tr {
            text-align: left;
        }

        table,
        th,
        td {
            border-collapse: collapse;
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
        }

        th {
            background-color: gainsboro;
        }
        .header {
            display: flex; /* Menggunakan flexbox agar logo dan nama bisnis dapat diatur dalam satu baris */
            align-items: center; /* Menyamakan tinggi logo dan nama bisnis */
            justify-content: center;
        }

        .logo {
            margin-right: 10px; /* Memberikan jarak kanan antara logo dan nama bisnis */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="column">
                <div class="header">
                    <div class="logo">
                        <img src="{{ asset('images/'.settings()->get('app_logo')) }}" alt="Logo" style="height: 45px; width: auto;">
                    </div>
                    <div class="business-name">
                        <h2>{{ settings()->get('business_name') }}</h2>
                    </div>
                </div>
                <p>Desa Talok, Kecamatan Turen, Kabupaten Malang, Jawa Timur</p>
                <hr style="width: 85%; text-align: center;">
                <h3 style="text-align: center;">Laporan Pembayaran
                    {{ $tanggalMulai && $tanggalSelesai
                        ? date('d-m-Y', strtotime($tanggalMulai)) . ' - ' . date('d-m-Y', strtotime($tanggalSelesai))
                        : 'Semua Range Tanggal' }}
                </h3>
            </div>
            <div class="col">
                <table id="table_id" class="display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Transaksi</th>
                            <th>Tgl. Pembayaran</th>
                            <th>Pelanggan</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $pembayaran)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                <td>{{ $pembayaran->kd_pembayaran }}</td>
                                <td>{{ date('d-m-Y', strtotime($pembayaran->tgl_bayar)) }}</td>
                                <td>{{ isset($pembayaran->pemakaian->user) ? $pembayaran->pemakaian->user->name : 'Tidak ditemukan' }}</td>
                                <td>Rp. {{ number_format($pembayaran->subTotal, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
