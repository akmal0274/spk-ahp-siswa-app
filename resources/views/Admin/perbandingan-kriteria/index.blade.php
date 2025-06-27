@extends('layouts.adminLayout')

@section('content')
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title text-gray-900">Perbandingan Nilai Kriteria</h5>
                <a href="{{ route('kriteria.index.admin') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('perbandingan-kriteria.store.admin') }}">
                    @csrf
                    <div class="container">
                        <div class="row font-weight-bold text-gray-900 mb-2">
                            <div class="col-md-4">Pasangan Kriteria</div>
                            <div class="col-md-4">Arah Perbandingan</div>
                            <div class="col-md-4">Nilai Kepentingan</div>
                        </div>

                        @foreach($kriteria as $k1)
                            @foreach($kriteria as $k2)
                                @if($k1->id != $k2->id)
                                    @php
                                        $existing = $perbandingan->firstWhere(fn($item) =>
                                            ($item->kriteria1_id == $k1->id && $item->kriteria2_id == $k2->id) ||
                                            ($item->kriteria1_id == $k2->id && $item->kriteria2_id == $k1->id)
                                        );

                                        // Tentukan arah default berdasarkan existing data
                                        if ($existing) {
                                            $arah = $existing->kriteria1_id == $k1->id ? 'AB' : 'BA';
                                            $nilai = $existing->nilai;
                                        } else {
                                            $arah = 'AB'; // Default arah
                                            $nilai = 1;    // Default nilai
                                        }
                                    @endphp

                                    <!-- Hanya tampilkan satu versi (A-B atau B-A) -->
                                    @if($k1->id < $k2->id)
                                        <div class="row align-items-center mb-2">
                                            <div class="col-md-4 text-gray-900">
                                                <strong>{{ $k1->nama_kriteria }}</strong> vs 
                                                <strong>{{ $k2->nama_kriteria }}</strong>
                                            </div>

                                            <div class="col-md-4">
                                                <select name="arah[{{ $k1->id }}][{{ $k2->id }}]" 
                                                        class="form-control arah-select">
                                                    <option value="AB" {{ $arah == 'AB' ? 'selected' : '' }}>
                                                        {{ $k1->nama_kriteria }} lebih penting
                                                    </option>
                                                    <option value="BA" {{ $arah == 'BA' ? 'selected' : '' }}>
                                                        {{ $k2->nama_kriteria }} lebih penting
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <select name="nilai[{{ $k1->id }}][{{ $k2->id }}]" 
                                                        class="form-control nilai-select">
                                                    @for($n = 1; $n <= 9; $n++)
                                                        <option value="{{ $n }}" {{ $nilai == $n ? 'selected' : '' }}>
                                                            {{ $n }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <div class="alert alert-info">
                            <strong>Petunjuk Pengisian:</strong> 
                            <ol>
                                <li>Gunakan skala 1 sampai 9 untuk menunjukkan tingkat kepentingan antar kriteria:
                                    <ul>
                                    <li>1 = Sama penting</li>
                                    <li>2 = Nilai antara Sama dan Cukup penting</li>
                                    <li>3 = Cukup lebih penting</li>
                                    <li>4 = Nilai antara Cukup penting dan Penting</li>
                                    <li>5 = Lebih penting</li>
                                    <li>6 = Nilai antara Penting dan Sangat penting</li>
                                    <li>7 = Sangat lebih penting</li>
                                    <li>8 = Nilai antara Sangat penting dan Mutlak</li>
                                    <li>9 = Mutlak lebih penting</li>
                                    </ul>
                                </li>
                                <li>Pilih arah perbandingan terlebih dahulu, lalu isi nilai kepentingan sesuai penilaian.</li>
                                <li>Jika kamu memilih "Nilai Rapor lebih penting" dengan nilai 4, maka sistem akan otomatis mengisi kebalikannya ("Kehadiran vs Nilai Rapor") dengan 1/4.</li>
                                <li>Tidak perlu mengisi nilai pecahan secara manual. Sistem akan menghitung otomatis.</li>
                                <li>Pastikan semua pasangan kriteria sudah dibandingkan sebelum klik "Simpan Perbandingan".</li>
                            </ol>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perbandingan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    
@endsection