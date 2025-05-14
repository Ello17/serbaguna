@extends('layouts.auth')
@section('title', 'Login')
@push('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
    <div class="wrapper">
        <div class="form-header">
            <div class="titles">
                <div class="title-login">Login</div>
            </div>
        </div>
            <form method="POST" action="{{route('login.post')}}">
                @csrf
                <div class="input-box">
                    <input type="text" class="input-field" id="name" name="username" required autofocus>
                    <label for="name" class="label">Username</label>
                    <i class='bx bx-user icon'></i>
                </div>
                <div class="input-box">
                    <input type="password" class="input-field" id="password" name="password" required autofocus>
                    <label for="password" class="label">Password</label>
                    <i class='bx bxs-key icon'></i>
                </div>

                 @if (Session::has('error'))
                        <div class="mb-3">
                            <p class="text-danger text-center">{{ Session::get('error') }}</p>
                        </div>
                    @endif
                    <div class="input-box">
                        <button type="submit" class="btn-sumbit">Login <i class='bx bx-log-in' ></i></button>
                    </div>
            </form>

    </div>
 
@endsection
