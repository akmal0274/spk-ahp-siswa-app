@extends('layouts.authLayout')

@section('content')
<div class="container min-vh-100 d-flex">
    <div class="row justify-content-center align-items-center w-100 mx-auto">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="text-center mb-4">
                        <b>REGISTER</b><br>
                        <small>SPK AHP SD Negeri Blok I Cilegon</small>
                    </h2>
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
