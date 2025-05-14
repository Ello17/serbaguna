@extends('layouts.auth')
@section('title', 'Login')

@section('content')
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg w-25">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0 text-center">Login - Serbaguna Produk</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Username</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="username" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" required>
                    </div>
                    @if (Session::has('error'))
                        <div class="mb-3">
                            <p class="text-danger text-center">{{ Session::get('error') }}</p>
                        </div>
                    @endif
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
