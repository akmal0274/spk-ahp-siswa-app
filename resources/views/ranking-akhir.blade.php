@extends('layouts.userLayout')

@section('content')
    <div class="col-md-12 my-4">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="text-gray-900">Hasil Akhir Pemilihan Siswa Terbaik</h5>
                <a href="{{ route('ranking-akhir.export-excel') }}" class="btn btn-m btn-success">Export Excel</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Peringkat</th>
                            <th>Nama Siswa</th>
                            <th>Skor Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $ranked = collect($nilaiAkhir)->sortDesc();
                            $peringkat = 1;
                        @endphp
                        @foreach ($ranked as $alt_id => $skor)
                            <tr>
                                <td>{{ $peringkat++ }}</td>
                                <td>{{ $alternatif->find($alt_id)->nama_siswa }}</td>
                                <td>{{ number_format($skor, 4) }}</td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection