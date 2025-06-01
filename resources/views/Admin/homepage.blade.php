@extends('layouts.adminLayout')

@section('content')
<div class="container-fluid d-flex flex-column" style="min-height: calc(100vh - 60px);">
    <div class="row flex-grow-1 justify-content-center align-items-center">
        <div class="col-md-12 text-center">
            <div class="row">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title text-gray-900">Total Kriteria</h5>
                    </div>
                    <div class="card-body">
                        <h1 class="text-primary">{{ session('kriteria') }}</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title text-gray-900">Total Alternatif</h5>
                    </div>
                    <div class="card-body">
                        <h1 class="text-primary">{{ session('alternatif') }}</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title text-gray-900">Total User</h5>
                    </div>
                    <div class="card-body">
                        <h1 class="text-primary">{{ session('userCount') }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
