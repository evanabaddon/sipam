@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title fw-semibold text-white">Data tarif</h5>
                        </div>
                        <div class="col-6 text-right">
                            <a href="/tarif" type="button" class="btn btn-warning float-end">Kembali</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="/tarif/{{ $tarif->id }}">
                        @method('put')
                        @csrf

                        <label for="m3_umum" class="mb-2">Kategori Nasabah<span style="color: red">*</span></label><br>
                        <div class="input-group mb-3">
                            <select class="form-control" name="kategori_id">
                                <option value="">Pilih Kategori</option>
                                @foreach($kategori as $item)
                                    <option value="{{ $item->id }}" {{ old('kategori_id', $tarif->kategori_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->kategori }}
                                    </option>
                                @endforeach
                            </select>
                            
                            @error('kategori_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <label for="tarif_1" class="mb-2">Tarif 1-10 m³ / Bulan<span
                            style="color: red">*</span></label><br>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp</span>
                            <input type="number" class="form-control" name="tarif_1"
                                value="{{ old('tarif_1', $tarif->tarif_1) }}">
                            @error('tarif_1')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <label for="tarif_2" class="mb-2">Tarif 11-20 m³ / Bulan<span
                            style="color: red">*</span></label><br>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp</span>
                            <input type="number" class="form-control" name="tarif_2"
                                value="{{ old('tarif_2', $tarif->tarif_2) }}">
                            @error('tarif_2')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <label for="tarif_3" class="mb-2">Tarif 21-30 m³ / Bulan<span
                            style="color: red">*</span></label><br>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp</span>
                            <input type="number" class="form-control" name="tarif_3"
                                value="{{ old('tarif_3', $tarif->tarif_3) }}">
                            @error('tarif_3')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <label for="tarif_4" class="mb-2">Tarif 31-dst m³ / Bulan<span
                            style="color: red">*</span></label><br>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp</span>
                            <input type="number" class="form-control" name="tarif_4"
                                value="{{ old('tarif_4', $tarif->tarif_4) }}">
                            @error('tarif_4')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <label for="admin" class="mb-2">Biaya Admin<span
                            style="color: red">*</span></label><br>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp</span>
                            <input type="number" class="form-control" name="admin"
                                value="{{ old('admin', $tarif->admin) }}">
                            @error('admin')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <label for="beban" class="mb-2">Biaya Beban<span
                            style="color: red">*</span></label><br>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp</span>
                            <input type="number" class="form-control" name="beban"
                                value="{{ old('beban', $tarif->beban) }}">
                            @error('beban')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <label for="denda" class="mb-2">Biaya Denda<span
                            style="color: red">*</span></label><br>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp</span>
                            <input type="number" class="form-control" name="denda"
                                value="{{ old('denda', $tarif->denda) }}">
                            @error('denda')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary m-1 float-end">Perbarui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#table_id').DataTable();
        });
    </script>
@endsection
