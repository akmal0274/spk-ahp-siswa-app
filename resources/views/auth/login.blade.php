@extends('layouts.authLayout')

@section('content')
<div class="container min-vh-100 d-flex">
    <div class="row justify-content-center align-items-center w-100 mx-auto">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah" style="max-width: 100px;">

                            <div style="width: 2px; height: 60px; background-color: #333; margin: 0 20px;"></div>
                            <div class="text-left">
                                <b style="font-size: 32px;">LOGIN</b>
                            </div>
                        </div>
                        <small class="d-block mt-2">Sistem Pendukung Keputusan SD Negeri Blok I Cilegon</small>
                    </div>

                    <hr>
                    
                    @if(session('error'))
                    <div class="alert alert-danger">
                        <{{session('error')}}
                    </div>
                    @endif

                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Log In</button>
                        <hr>
                        <p class="text-center mb-0">Belum punya akun? <a href="{{ route('register.form') }}">Register</a> sekarang!</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection