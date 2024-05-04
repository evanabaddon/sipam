@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title fw-semibold text-white">Data Pelanggan</h5>
                        </div>
                        <div class="col-6 text-right">
                            <a href="/pelanggan/create" type="button" class="btn btn-warning float-end">Tambah pelanggan</a>
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
                                    <th>No. Pelanggan</th>
                                    <th>Nama</th>
                                    <th>Tgl. Pasang</th>
                                    <th>Jenis Pelanggan</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelanggans as $pelanggan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pelanggan->no_pelanggan }}</td>
                                        <td>{{ $pelanggan->name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($pelanggan->tgl_pasang)) }}</td>
                                        <td>{{ $pelanggan->kategori->kategori }}</td>
                                        <td>
                                            <a href="/pelanggan/kartu-pelanggan/{{ $pelanggan->id }}" type="button"
                                                class="btn btn-dark mb-1"><i class="ti ti-printer"></i></a>
                                            <a href="/pelanggan/{{ $pelanggan->id }}" type="button"
                                                class="btn btn-success mb-1"><i class="ti ti-eye"></i></a>
                                            <a href="/pelanggan/{{ $pelanggan->id }}/edit" type="button"
                                                class="btn btn-warning mb-1"><i class="ti ti-edit"></i></a>
                                            <form id="{{ $pelanggan->id }}" action="/pelanggan/{{ $pelanggan->id }}"
                                                method="POST" class="d-inline">
                                                @method('delete')
                                                @csrf
                                                <button type="button" class="btn btn-danger swal-confirm mb-1"
                                                    data-form="{{ $pelanggan->id }}"><i class="ti ti-trash"></i></button>
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
