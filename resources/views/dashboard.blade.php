@extends('layouts.userLayout')

@section('content')
    <div class="col-md-12">
        <div class="row justify-content-center align-items-center h-100">
            <style>
                .logo {
                    animation: float 3s ease-in-out infinite;
                    max-width: 600px; /* Sesuaikan ukuran */
                }
                @keyframes float {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-20px); }
                }
            </style>
            <img src="{{ asset('images/gambar-sekolah.jpeg') }}" alt="Logo" class="logo">
        </div>
        <div class="row justify-content-center align-items-center">
            <h3 class="text-center text-dark text-weight-bold my-2">Selamat datang, {{ auth()->user()->name }}!</h3>
        </div>
        <div class="row justify-content-center align-items-center">
            <h5 class="text-center text-dark my-0.5">Sistem Pendukung Keputusan Pemilihan Siswa Terbaik</h5>
        </div>
        <div class="row justify-content-center align-items-center">
            <h5 class="text-center text-dark my-0.5">SD Negeri Blok I Cilegon</h5>
        </div>
    </div>

    
@endsection