@extends('layouts.adminLayout')

@section('content')
    <div class="col-md-12 my-4">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="text-gray-900">Hasil Akhir Pemilihan Alternatif</h5>
                <div>
                    <!-- Filter tahun ajaran -->
                    <form method="GET" class="d-inline">
                        <select name="tahun_ajaran" onchange="this.form.submit()" class="form-control d-inline w-auto">
                            <option value="">Semua Tahun Ajaran</option>
                            @foreach ($tahunAjarans as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun_ajaran') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    <a href="{{ route('ranking-akhir.export-excel.admin', ['tahun_ajaran' => request('tahun_ajaran')]) }}" class="btn btn-m btn-success ml-2">
                        Export Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                @foreach ($ranking as $tahun => $rows)
                    <h5 class="mt-2 text-gray-900">Tahun Ajaran: {{ $tahun }}</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Nama Siswa</th>
                                <th>Skor Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $i => $row)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $row['alternatif']->nama_siswa }}</td>
                                    <td>{{ number_format($row['skor'], 4) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
        </div>
    </div>
@endsection
