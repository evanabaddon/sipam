@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title fw-semibold text-white">Pengaturan Aplikasi</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="/settings" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="app_name" class="form-label">Nama Aplikasi <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="app_name" value="{{ settings()->get('app_name') }}">
                            @error('app_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="app_logo" class="form-label">Logo Aplikasi <span style="color: red">*</span></label>
                            <input type="file" class="form-control" name="app_logo" value="{{ settings()->get('app_logo') }}">
                            @error('app_logo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="business_name" class="form-label">Nama Bisnis <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="business_name" value="{{ settings()->get('business_name') }}">
                            @error('business_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="business_address" class="form-label">Alamat Bisnis <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="business_address" value="{{ settings()->get('business_address') }}">
                            @error('business_address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="app_email" class="form-label">App Email <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="app_email" value="{{ settings()->get('app_email') }}">
                            @error('app_email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="wa_api" class="form-label">Whatsapp Api <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="wa_api" value="{{ settings()->get('wa_api') }}">
                            @error('wa_api')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary m-1 float-end">Simpan</button>
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
