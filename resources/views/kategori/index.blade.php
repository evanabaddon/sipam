@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title fw-semibold text-white">Data Kategori Pelanggan</h5>
                        </div>
                        <div class="col-6 text-right">
                            <a href="/kategori/create" type="button" class="btn btn-warning float-end">Tambah Kategori</a>
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
                                    <th>Nama Kategori</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategoris as $kategori)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kategori->kategori }}</td>
                                        <td>
                                            <a href="/kategori/{{ $kategori->id }}/edit" type="button"
                                                class="btn btn-warning mb-1"><i class="ti ti-edit"></i></a>
                                            <form id="{{ $kategori->id }}" action="/kategori/{{ $kategori->id }}" method="POST" class="d-inline">
                                                @method('delete')
                                                @csrf
                                                <button type="button" class="btn btn-danger swal-confirm mb-1" data-form="{{ $kategori->id }}"><i class="ti ti-trash"></i></button>
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
