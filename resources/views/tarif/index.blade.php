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
                            <a href="/tarif/create" type="button" class="btn btn-warning float-end">Tambah Tarif</a>
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

                    <div class="table-responsive">
                        <table id="table_id" class="table display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kategori</th>
                                    <th>1-10 m³</th>
                                    <th>11-20 m³</th>
                                    <th>21-30 m³</th>
                                    <th>31-dst m³</th>
                                    <th>Biaya Admin</th>
                                    <th>Beban</th>
                                    <th>Denda</th>
                                    <th>Perbarui</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tarifs as $tarif)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ optional($tarif->kategori)->kategori ?? 'Kategori Tidak Tersedia' }}</td>
                                        <td>Rp. {{ $tarif->tarif_1 }}</td>
                                        <td>Rp. {{ $tarif->tarif_2 }}</td>
                                        <td>Rp. {{ $tarif->tarif_3 }}</td>
                                        <td>Rp. {{ $tarif->tarif_4 }}</td>
                                        <td>Rp. {{ $tarif->admin }}</td>
                                        <td>Rp. {{ $tarif->beban }}</td>
                                        <td>Rp. {{ $tarif->denda }}</td>
                                        <td>
                                            <a href="/tarif/{{ $tarif->id }}/edit" type="button"
                                                class="btn btn-warning mb-1"><i class="ti ti-edit"></i></a>
                                            <form id="{{ $tarif->id }}" action="/tarif/{{ $tarif->id }}" method="POST" class="d-inline">
                                                @method('delete')
                                                @csrf
                                                <button type="button" class="btn btn-danger swal-confirm mb-1" data-form="{{ $tarif->id }}"><i class="ti ti-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
