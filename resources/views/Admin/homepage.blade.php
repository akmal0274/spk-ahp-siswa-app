@extends('layouts.adminLayout')

@section('content')
<div class="col-md-12">
    <div class="row justify-content-center align-items-center h-100">
        <div class="col-md-10">
            <div class="row text-center">

                <!-- Card Total Kriteria -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header">
                            <h5 class="text-gray-900">Total Kriteria</h5>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                            <h1 class="text-primary">{{ $kriteria }}</h1>
                        </div>
                    </div>
                </div>

                <!-- Card Total Alternatif -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header">
                            <h5 class="text-gray-900">Total Alternatif</h5>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                            <h1 class="text-primary">{{ $alternatif }}</h1>
                        </div>
                    </div>
                </div>

                <!-- Card Total User -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header">
                            <h5 class="text-gray-900">Total User</h5>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                            <h1 class="text-primary">{{ $userCount }}</h1>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
