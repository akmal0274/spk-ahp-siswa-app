@extends('layouts.adminLayout')

@section('content')
<div class="container my-4">
    <h2 class="text-gray-900 mb-4">
        <i class="fas fa-cogs"></i> Perhitungan Keputusan Per Tahun Ajaran
    </h2>

    <!-- Tabs Tahun Ajaran -->
    <ul class="nav nav-tabs mb-4" id="tahunTabs" role="tablist">
        @foreach ($tahunAjarans as $tahun)
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                   id="tab-{{ $tahun }}"
                   data-bs-toggle="tab"
                   href="#tahun-{{ $tahun }}"
                   role="tab"
                   aria-controls="tahun-{{ $tahun }}"
                   aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                   Tahun Ajaran {{ $tahun }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content" id="tahunTabsContent">
        @foreach ($data as $tahun => $content)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
             id="tahun-{{ $tahun }}"
             role="tabpanel"
             aria-labelledby="tab-{{ $tahun }}">

            <!-- Bobot Kriteria AHP -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0">Bobot Kriteria (AHP)</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Bobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriteria as $k)
                            <tr>
                                <td>{{ $k->kode_kriteria }}</td>
                                <td>{{ $k->nama_kriteria }}</td>
                                <td>{{ number_format($eigen_vector[$k->id] ?? 0, 4) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Matriks Keputusan -->
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="m-0">Matriks Keputusan - Tahun Ajaran {{ $tahun }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Alternatif</th>
                                @foreach ($kriteria as $k)
                                    <th>{{ $k->kode_kriteria }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($content['alternatifs'] as $alt)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $alt->nama_siswa }}</td>
                                    @foreach ($kriteria as $k)
                                        <td class="text-center">{{ $content['matrix'][$alt->id][$k->id] ?? '-' }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="thead-light">
                            <tr>
                                <th colspan="2">Max</th>
                                @foreach ($kriteria as $k)
                                    <th class="text-center">{{ $content['max'][$k->id] ?? '-' }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                <th colspan="2">Min</th>
                                @foreach ($kriteria as $k)
                                    <th class="text-center">{{ $content['min'][$k->id] ?? '-' }}</th>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Matriks Normalisasi -->
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="m-0">Normalisasi - Tahun Ajaran {{ $tahun }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Alternatif</th>
                                @foreach ($kriteria as $k)
                                    <th>{{ $k->kode_kriteria }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($content['alternatifs'] as $alt)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $alt->nama_siswa }}</td>
                                    @foreach ($kriteria as $k)
                                        <td class="text-center">
                                            {{ number_format($content['normalisasi'][$alt->id][$k->id] ?? 0, 4) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Hasil Preferensi & Ranking -->
            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="m-0">Hasil Preferensi & Ranking - Tahun Ajaran {{ $tahun }}</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama Alternatif</th>
                                <th colspan="{{ count($kriteria) }}">Normalisasi x Bobot</th>
                                <th rowspan="2">Nilai Preferensi</th>
                            </tr>
                            <tr>
                                @foreach ($kriteria as $k)
                                    <th>{{ $k->kode_kriteria }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($content['ranking'] as $row)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $row['nama'] ?? '-' }}</td>

                                    @foreach ($kriteria as $k)
                                        <td class="text-center">
                                              {{ number_format($row['detail'][$k->id]['normalisasi'] ?? 0, 4) }}
                                            ×
                                            {{ number_format($row['detail'][$k->id]['bobot'] ?? 0, 4) }}
                                            = 
                                            {{ number_format($row['detail'][$k->id]['hasil'] ?? 0, 4) }}
                                        </td>
                                    @endforeach

                                    <td class="text-center">{{ number_format($row['preferensi'], 4) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
    <!-- Bootstrap 5 bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
