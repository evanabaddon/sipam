@extends('layouts.main')

@section('content')
<style>
    .payment-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .payment-icon {
        margin-bottom: 5px; /* Jarak antara ikon dan teks */
    }
    .accordion-button.collapsed {
        background-color: #2A3547 !important;
        color: white !important;
    }
</style>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title fw-semibold text-white">Detail Pemakaian</h5>
                        </div>
                    </div>
                </div>
                <form action="cek-tagihan/bayar" method="POST">
                @csrf
                <div class="card-body">
                    <div id="paymentForm">
                            <input type="hidden" id="pemakaian_id" name="pemakaian_id" value="{{ $tagihan->id }}">
                            <input type="hidden" class="form-control" name="tgl_bayar" id="tgl_bayar" readonly>
                            <table class="mb-3 table mx-auto">
                            <tr class="bg-dark text-white">
                                <td><b>Periode</b></td>
                                <td>:</td>
                                <td>{{ $tagihan->periode->periode }}</td>
                            </tr>

                            <tr>
                                <td><b>Status Pembayaran</b></td>
                                <td>:</td>
                                <td>
                                    @if ($tagihan->status === 'belum dibayar')
                                        <span class="badge text-bg-warning p-2">{{ $tagihan->status }}</span>
                                    @else
                                        <span class="badge text-bg-success p-2">{{ $tagihan->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><b>Penggunaan Awal m³</b></td>
                                <td>:</td>
                                <td>{{ $tagihan->penggunaan_awal }} m³</td>
                            </tr>
                            <tr>
                                <td><b>Penggunaan Akhir m³</b></td>
                                <td>:</td>
                                <td>{{ $tagihan->penggunaan_akhir }} m³</td>
                            </tr>
                            <tr>
                                <td><b>Penggunaan m³</b></td>
                                <td>:</td>
                                <td>{{ $tagihan->jumlah_penggunaan }} m³</td>
                            </tr>
                            <tr>
                                <td><b>Tarif Per m³</b></td>
                                <td>:</td>
                                <td>Rp. {{ $tarifPemakaian }} </td>
                            </tr>
                            <tr>
                                <td><b>Tarif Beban </b></td>
                                <td>:</td>
                                <td>Rp. {{ $tarif->beban }}</td>
                            </tr>
                            <tr>
                                <td><b>Tarif Denda</b></td>
                                <td>:</td>
                                <td>Rp. <span id="denda">{{ $tarif->denda }}</span></td>
                            </tr>
                            <tr class="bg-dark text-white">
                                <td><b>Sub Total</b></td>
                                <td>:</td>
                                <td>Rp. <span id="jumlah_pembayaran">{{ $tagihan->jumlah_pembayaran }}</span></td>
                            </tr>
                        </table>
                        @if ($tagihan->status == 'belum dibayar')
                            <div class="button">
                                <button type="button" class="btn btn-success m-1 float-end" id="bayar">Bayar Sekarang</button>
                            </div>
                            {{-- <div class="accordion" id="paymentAccordion">
                                @foreach ($channels as $index => $group)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $group->group_id }}">
                                            <button class="accordion-button @if($index === 0) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $group->group_id }}" aria-expanded="@if($index === 0) true @else false @endif" aria-controls="collapse{{ $group->group_id }}">
                                                {{ $group->group_name }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $group->group_id }}" class="accordion-collapse collapse @if($index === 0) show @endif" aria-labelledby="heading{{ $group->group_id }}" data-bs-parent="#paymentAccordion">
                                            <div class="accordion-body">
                                                @foreach ($group->payment as $payment)
                                                <button class="btn btn-rounded" onclick="prosesPembayaran('{{ $payment->code }}')">
                                                    <div class="payment-item">
                                                        <img src="{{ $payment->icon_url ?? 'https://upload.wikimedia.org/wikipedia/commons/5/56/Chain_link_icon_slanted.png' }}" alt="{{ $payment->name }}" class="payment-icon" style="width: 40px; height: 40px;">
                                                        <span class="payment-name">{{ $payment->name }}</span>
                                                        <input type="hidden" name="{{ $payment->code }}">
                                                    </div>
                                                </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div> --}}
                        @else
                        @endif
                    </div>
                

                </form>
            </div>
            </div>
        </div>
    </div>
    {{-- <script>
        function prosesPembayaran(paymentCode) {
            var tagihanId = document.getElementById('pemakaian_id').value;
            // Kirim data ke route proses-bayar menggunakan AJAX atau window.location.href
            // Contoh menggunakan AJAX
            $.ajax({
                type: 'POST',
                url: '{{ route("proses-bayar") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    payment_code: paymentCode,
                    pemakaian_id: tagihanId
                },
                success: function(response) {
                    // Tindakan setelah pemrosesan berhasil
                    if (response.success) {
                        // Simpan data respons ke session
                        // sessionStorage.setItem('data', JSON.stringify(response.data));
                        // Redirect ke halaman detail pembayaran
                        window.location.href = '{{ route("proses-pembayaran") }}';
                    } else {
                        // Jika terjadi kesalahan dalam proses pembayaran, tampilkan pesan kesalahan
                        alert('Terjadi kesalahan dalam proses pembayaran.');
                    }
                },
                error: function(xhr, status, error) {
                    // Tindakan jika terjadi kesalahan
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }
    </script> --}}
    
    <script>
        $(document).ready(function () {
            $('#table_id').DataTable();
        });

        function calculateDenda() {
            var tanggal_batas_bayar = new Date("{{ $tagihan->batas_bayar }}");
            var tgl_bayar = new Date();

            if (tgl_bayar > tanggal_batas_bayar) {
                var selisihBulan    = calculateMonthDifference(tgl_bayar, tanggal_batas_bayar);
                var dendaPerBulan   = parseFloat($('#denda').text());
                var totalDenda      = selisihBulan * dendaPerBulan;
                var totalPembayaran = parseFloat($('#jumlah_pembayaran').text()) + totalDenda;
                $('#denda').text(totalDenda.toFixed());
                $('#jumlah_pembayaran').text(totalPembayaran.toFixed(2));
            } else {
                $('#denda').text('0');
            }
        }

        function calculateMonthDifference(date1, date2) {
            var diff = (date1.getFullYear() - date2.getFullYear()) * 12;
            diff -= date2.getMonth();
            diff += date1.getMonth();
            return diff <= 0 ? 0 : diff;
        }
        calculateDenda();
    </script>

    <!-- Generate Tanggal Hari Ini -->
    <script>
        var today   = new Date();
        var year    = today.getFullYear();
        var month   = (today.getMonth() + 1).toString().padStart(2, '0');
        var day     = today.getDate().toString().padStart(2, '0');

        var formattedDate = year + '-' + month + '-' + day;
        document.getElementById('tgl_bayar').value = formattedDate;
    </script>
    <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.server_key')}}"> </script>
    <script>
        $(document).ready(function() {
            $('#bayar').click(function() {
                var token             = $('meta[name="csrf-token"]').attr('content');
                var tgl_bayar         = $('#tgl_bayar').val();
                var pemakaianId       = $('#pemakaian_id').val();
                var denda             = $('#denda').text();
                var jumlah_pembayaran = $('#jumlah_pembayaran').text();

                $.ajax({
                    type: 'POST',
                    url: '/cek-tagihan/bayar',
                    data: {
                        _token: token,
                        tgl_bayar: tgl_bayar,
                        pemakaian_id: pemakaianId,
                        denda: denda,
                        jumlah_pembayaran: jumlah_pembayaran
                    },
                    
                    success: function(response) {
                        var snapToken = response.snapToken;

                        window.snap.pay(snapToken, {
                            onSuccess: function(result) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pambayaran Berhasil !',
                                    text: 'Pembayaran Anda Telah Diproses !',
                                }).then((result) => {
                                    location.reload();
                                    console.log(result);
                                });
                                console.log(result)
                            },
                            onPending: function(result) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Pembayaran Tertunda',
                                    text: 'Menunggu pembayaran Anda...',
                                }).then((result) => {
                                    console.log(result);
                                });
                                console.log(result)
                            },
                            onError: function(result) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Pembayaran gagal',
                                    text: 'Ups! Ada yang salah dengan pembayaran Anda',
                                }).then((result) => {
                                    console.log(result);
                                });
                                console.log(result)
                            },
                            onClose: function() {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Pembayaran Dibatalkan',
                                    text: 'Anda menutup popup tanpa menyelesaikan pembayaran',
                                });
                                console.log(result)
                            }
                        });
                    },

                    error: function(error) {
                        console.log('Terjadi kesalahan dalam pembayaran:', error);
                    }
                });
            });
        });
    </script>


@endsection
