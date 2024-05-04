@extends('layouts.main')

@section('content')
    <style>
        .card-body p {
            margin-bottom: 10px;
        }

        .card-body p strong {
            display: inline-block;
            width: 150px;
        }

        .code-payment {
            display: flex;
            align-items: center;
            border: 2px solid #ccc;
            border-radius: 8px;
            padding: 8px;
        }

        #paymentCode {
            font-weight: bold;
            flex-grow: 1;
            margin-right: 8px;
        }

        .copy-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .copy-button i {
            font-size: 16px;
        }

        .copy-button:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Detail Pembayaran</h5>
        </div>
        <div class="card-body">
            @if (session()->has('payment_data'))
                @php
                    $data = session()->get('payment_data');
                @endphp
                <div style="text-align: center">
                    <p><strong>Metode Pembayaran:</strong><br>{{ $data['payment_name'] }}</p>
                    <p><strong>Status Pembayaran:</strong> {{ $data['status'] }}</p>
                    <p><strong>Invoice: {{ $data['merchant_ref'] }}</strong></p>
                    <p><strong>Batas Pembayaran: <span style="color: red;">{{ date('d F Y H:i:s', $data['expired_time']) }}</span></strong></p>


                </div>
                <hr>
                @if (isset($data['order_items'][0]))
                    <div style="text-align: center">
                        <strong>{{ $data['order_items'][0]['name'] }}</strong>
                        <hr>
                    </div>
                        <p><strong>Total Tagihan:</strong> Rp. {{ number_format($data['order_items'][0]['price'],) }}</p>
                    
                @endif
                <p><strong>Biaya Bank:</strong> Rp. {{ number_format($data['total_fee']) }}</p>
                <p><strong>Total Pembayaran:</strong> Rp. {{ number_format($data['amount']) }}</p>
                <hr>
                <p><strong style="display: inline;">Salin kode pembayaran dibawah ini!</strong></p>
                <div class="code-payment">
                    <span id="paymentCode">{{  $data['pay_code'] }}</span>
                    <button class="copy-button" onclick="copyPaymentCode()"><i class="ti ti-copy"></i></button>
                </div>
                

                <!-- Informasi tambahan sesuai kebutuhan -->
                <hr>
                <h6>Instruksi Pembayaran:</h6>
                @foreach ($data['instructions'] as $instruction)
                    <h6>{{ $instruction['title'] }}</h6>
                    <ol>
                        @foreach ($instruction['steps'] as $step)
                            <li>{!! $step !!}</li>
                        @endforeach
                    </ol>
                @endforeach
            @else
                <p>Data pembayaran tidak tersedia.</p>
            @endif
        </div>
    </div>

    <script>
        function copyPaymentCode() {
            var paymentCode = document.getElementById('paymentCode');
            var tempInput = document.createElement("input");
            tempInput.value = paymentCode.innerText;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            alert("Kode pembayaran telah disalin: " + paymentCode.innerText);
        }
    </script>
@endsection
