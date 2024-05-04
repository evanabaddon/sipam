@extends('layouts.auth')

@section('content')
<div class="col-md-8 mx-auto">
    <div class="card mb-0">
        <div class="card-body">
        {{-- <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
            <img src="assets/images/logos/dark-logo.svg" width="180" alt="">
        </a> --}}

            @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                </div>

                <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" value="{{ old('password') }}" required autocomplete="password">
                </div>
                <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                    <label class="form-check-label text-dark" for="flexCheckChecked">
                    Ingat perangkat ini ?
                    </label>
                </div>
                <a class="text-primary fw-bold" href="http://127.0.0.1:8000/forgot-password">Lupa Password ?</a>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</button>
            </form>
        </div>
    </div>
</div>
@endsection
            
{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ml-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}
