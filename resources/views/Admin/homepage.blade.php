@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid d-flex flex-column" style="min-height: calc(100vh - 60px);">
    <div class="row flex-grow-1 justify-content-center align-items-center">
        <div class="col-md-12 text-center">
            <h3 style="color: black;">Selamat datang, {{ auth()->user()->name }}!</h3>
            <h2 class="mt-3" style="color: black;">Sistem SPK Siswa Dengan AHP</h2>
        </div>
    </div>
</div>
@endsection
