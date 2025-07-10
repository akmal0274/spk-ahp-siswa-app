@extends('layouts.adminLayout')

@section('content')
<div class="col-md-12 my-4">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="text-gray-900">Hasil Akhir Pemilihan Siswa Terbaik</h5>
            <div class="d-flex align-items-center">
                <form method="GET" class="d-inline mr-2">
                    <select name="tahun_ajaran" onchange="this.form.submit()" class="form-control d-inline w-auto">
                        <option value="">Semua Tahun Ajaran</option>
                        @foreach ($tahunAjarans as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun_ajaran') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </form>

                @if(Auth::user()->role === 'admin')
                <form method="GET" class="d-inline">
                    <select name="status_validasi" onchange="this.form.submit()" class="form-control d-inline w-auto">
                        <option value="">Semua Status</option>
                        <option value="Sudah" {{ request('status_validasi') == 'Sudah' ? 'selected' : '' }}>Sudah Divalidasi</option>
                        <option value="Belum" {{ request('status_validasi') == 'Belum' ? 'selected' : '' }}>Belum Divalidasi</option>
                    </select>
                </form>
                    <a href="{{ route('ranking-akhir.export-excel.admin', ['tahun_ajaran' => request('tahun_ajaran'), 'status_validasi' => request('status_validasi')]) }}" class="btn btn-success btn-m ml-2 mb-2 mb-md-0">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                @else
                    <a href="{{ route('ranking-akhir.export-excel', ['tahun_ajaran' => request('tahun_ajaran'), 'status_validasi' => request('status_validasi')]) }}" class="btn btn-success btn-m ml-2 mb-2 mb-md-0">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                @endif
            </div>
        </div>

        <div class="card-body">
            @forelse ($ranking as $tahun => $rows)
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="text-gray-900">Tahun Ajaran: {{ $tahun }}</h5>
                    @if(Auth::user()->role !== 'admin')
                        @if(isset($validasi[$tahun]) && $validasi[$tahun] == 1)
                            <span class="badge badge-success">Sudah Divalidasi</span>
                        @else
                            <form method="POST" action="{{ route('ranking.validasi') }}">
                                @csrf
                                <input type="hidden" name="tahun_ajaran" value="{{ $tahun }}">
                                <button class="btn btn-sm btn-warning mb-2"
                                        onclick="return confirm('Yakin validasi tahun ajaran {{ $tahun }}?')">
                                    Validasi Ranking Tahun Ajaran Ini
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">Peringkat</th>
                                <th>Nama Alternatif</th>
                                <th class="text-center">Skor Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $i => $row)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $row['alternatif']->nama ?? $row['alternatif']->nama_siswa ?? '-' }}</td>
                                    <td class="text-center">{{ number_format($row['skor'], 4) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <p class="text-muted">Tidak ada data ranking.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
