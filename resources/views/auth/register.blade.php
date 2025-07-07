@extends('layouts.authLayout')

@section('content')
<div class="container min-vh-100 d-flex my-3">
    <div class="row justify-content-center align-items-center w-100 mx-auto">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah" style="max-width: 100px;">

                            <div style="width: 2px; height: 60px; background-color: #333; margin: 0 20px;"></div>
                            <div class="text-left">
                                <b style="font-size: 32px;">REGISTER</b>
                            </div>
                        </div>
                        <small class="d-block mt-2">Sistem Pendukung Keputusan SD Negeri Blok I Cilegon</small>
                    </div>
                    <hr>

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('register') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Register</button>
                        <hr>
                        <p class="text-center mb-0">Sudah punya akun? <a href="{{ route('login') }}">Login</a> sekarang!</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
