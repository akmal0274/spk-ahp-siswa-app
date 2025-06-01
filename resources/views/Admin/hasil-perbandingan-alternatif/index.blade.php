@extends('layouts.adminLayout')

@section('content')
<div class="col-md-12 mx-auto">
    <div class="card shadow">
        <div class="card-header">
            <h5 class="modal-title text-gray-900">Pilih Kriteria untuk Menampilkan Hasil Perbandingan Alternatif</h5>
        </div>
        <div class="card-body">
            <div class="list-group">
                @foreach($kriteriaList as $kriteria)
                    <a href="{{ route('hasil-perbandingan-alternatif.show.admin', $kriteria->id) }}" 
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        {{ $kriteria->nama_kriteria }}
                        <span class="badge bg-primary rounded-pill">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection