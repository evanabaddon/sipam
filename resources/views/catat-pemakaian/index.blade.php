@extends('layouts.main')
<style>
    #preview {
  width: 300px;
  height: 300px;
  outline: 1px solid #6699FF;
}
</style>
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title fw-semibold text-white">Catat Pemakaian</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="/catat-pemakaian" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="my-3">
                                    <label for="scan-qrcode" class="mb-2"><b>Scan Qr Code Pelanggan</b></label>
                                    <video id="preview"></video>
                                    <div class="col-12 text-right">
                                        <a style="display: none;" id="startScanningButton" type="button" class="btn btn-warning"><i class="ti ti-camera"></i> Scan Ulang</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <input type="hidden" id="result">
                                    <input type="hidden" id="id">
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Pilih Nama Pelanggan <span
                                            style="color: red">*</span></label>
                                    <select id="user_id" class="js-example-basic-single" name="user_id"
                                        style="width: 100%;">
                                        <option value="" selected>-- Pilih Pelanggan --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->no_pelanggan }} |
                                                {{ $user->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="penggunaan_awal" class="form-label">Penggunaan Awal</label>
                                            <input type="number" class="form-control" name="penggunaan_awal"
                                                id="penggunaan_awal">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="jumlah_penggunaan" class="form-label">Jumlah Penggunaan</label>
                                            <input type="number" class="form-control" name="jumlah_penggunaan"
                                                id="jumlah_penggunaan" readonly>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mt-3">
                                <div class="alert alert-warning" role="alert">
                                    Form Yang Wajib Di Isi !
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="penggunaan_akhir" class="form-label">Penggunaan Akhir <span
                                                    style="color: red">*</span></label>
                                            <input type="number" class="form-control" name="penggunaan_akhir"
                                                id="penggunaan_akhir" required>
                                            @error('penggunaan_akhir')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror

                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="periode_id" class="form-label">Periode Pemakaian <span
                                                    style="color: red">*</span></label>
                                            <select class="form-select" name="periode_id"
                                                aria-label="Default select example">
                                                @foreach ($periodes as $periode)
                                                    <option value="{{ $periode->id }}">{{ $periode->periode }}</option>
                                                @endforeach
                                            </select>
                                            @error('periode_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="batas_bayar" class="form-label">Tanggal Batas Pembayaran <span
                                                    style="color: red">*</span></label>
                                            <input type="date" class="form-control" name="batas_bayar" id="batas_bayar">
                                            @error('batas_bayar')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="foto_meteran" class="form-label">Foto Meteran <span style="color: red">*</span></label>
                                            <input type="file" class="form-control" name="foto_meteran" id="foto_meteran" accept="image/*" capture="camera" required>
                                            @error('foto_meteran')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary m-1 float-end">Simpan</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('assets/js/instascan.min.js') }}"></script>
    {{-- <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script> --}}
    <script type="text/javascript">
        let scanner = new Instascan.Scanner({ 
            video: document.getElementById('preview')
        });

        scanner.addListener('scan', function (content) {
            console.log(content);

            // Lakukan AJAX request dengan isi content yang discan
            $.ajax({
                url: '/catat-pemakaian/get-data-pelanggan',
                method: 'GET',
                data: {
                    result: content
                },
                success: function(response) {
                    if (response.id !== null) {
                        $('#result').val(content);
                        $('#id').val(response.id);
                        $('#name').val(response.name);
                        $('#penggunaan_awal').val(response.penggunaan_akhir);

                        $('#user_id').val(response.id).trigger('change.select2');

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Data Pelanggan Ditemukan',
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // Set continuous ke false setelah pemindaian berhasil
                        scanner.stop();

                        // Sembunyikan preview video
                        $('#preview').css('display', 'none');

                        // Tampilkan tombol untuk memulai kembali pemindaian
                        $('#startScanningButton').css('display', 'block');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Data pelanggan tidak ditemukan!'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        // Event handler untuk memulai kembali pemindaian ketika tombol diklik
        $('#startScanningButton').click(function() {
            // Tampilkan kembali preview video
            $('#preview').css('display', 'block');

            // Sembunyikan tombol memulai kembali pemindaian
            $(this).css('display', 'none');

            // Mulai pemindaian kembali
            scanner.start();
        });

        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function (e) {
            console.error(e);
        });

    </script>
    {{-- <script>
        function onScanSuccess(decodedText, decodedResult) {
            $.ajax({
                url: '/catat-pemakaian/get-data-pelanggan',
                method: 'GET',
                data: {
                    result: decodedText
                },
                success: function(response) {
                    $('#result').val(decodedText);
                    $('#id').val(response.id);
                    $('#name').val(response.name);
                    $('#penggunaan_awal').val(response.penggunaan_akhir);

                    $('#user_id').val(response.id).trigger('change.select2');

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Data Pelanggan Ditemukan',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    return;

                    console.log(`Code matched = ${decodedText}`, decodedResult);
                }
            });
        }


        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // for example:
            console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            },
            /* verbose= */
            false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script> --}}

    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();

            $('.js-example-basic-single').change(function() {
                var user_id = $(this).val();

                $.ajax({
                    url: '/catat-pemakaian/get-data/' + user_id,
                    type: 'GET',
                    success: function(data) {
                        console.log(data);
                        $('penggunaan_akhir').val(data.penggunaan_akhir);

                        var penggunaan_akhir = parseFloat(data.penggunaan_akhir) || 0;

                        $('#penggunaan_awal').val(penggunaan_akhir);
                    }
                });
            });
        });
    </script>

    <script>
        const penggunaanAwal = document.getElementById('penggunaan_awal');
        const penggunaanAkhir = document.getElementById('penggunaan_akhir');
        const jumlahPenggunaan = document.getElementById('jumlah_penggunaan');

        penggunaanAwal.addEventListener('input', hitungJumlahPenggunaan);
        penggunaanAkhir.addEventListener('input', hitungJumlahPenggunaan);

        function hitungJumlahPenggunaan() {
            const awal = parseFloat(penggunaanAwal.value) || 0;
            const akhir = parseFloat(penggunaanAkhir.value) || 0;
            const hasil = akhir - awal;

            jumlahPenggunaan.value = hasil;
        }
    </script>
@endsection
