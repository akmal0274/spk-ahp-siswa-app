@extends('layouts.adminLayout')

@section('content')
    <div class="col-md-12">
        @if($cr < 0.1)
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title text-gray-900">Data Alternatif</h5>
                    <a href="{{ route('alternatif.create.admin') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Alternatif
                    </a>
                </div>   
                
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Jenis Kelamin</th>
                                <th>Tahun Ajaran</th>
                                @foreach ($kriteria as $k)
                                    <th>{{ $k->nama_kriteria }}</th>
                                @endforeach
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($alternatif) > 0)
                                @foreach ($alternatif as $a)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $a->nis }}</td>
                                    <td>{{ $a->nama_siswa }}</td>
                                    <td>{{ $a->kelas }}</td>
                                    <td>{{ $a->jenis_kelamin }}</td>
                                    <td>{{ $a->tahun_ajaran }}</td>
                                    @foreach ($kriteria as $k)
                                        @php
                                            $nilai = $a->nilai_alternatif->where('kriteria_id', $k->id)->first();
                                        @endphp
                                        <td>{{ $nilai ? $nilai->subkriteria->nama_subkriteria : '-' }}</td>
                                    @endforeach
                                    <td>
                                        <a href="{{ route('alternatif.edit.admin', $a->id) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('alternatif.destroy.admin', $a->id) }}" method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger " onclick="return confirm('Apakah anda yakin untuk menghapus alternatif ini?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11" class="text-center">Belum ada data alternatif</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card shadow p-4">
                <h4 class="text-danger">⚠ Konsistensi Belum Tercapai</h4>
                <p class="text-gray-900">Nilai Consistency Ratio (CR) melebihi 0.1 atau 10%, artinya perbandingan kriteria belum konsisten.</p>
                <p class="text-gray-900">Silakan perbaiki perbandingan kriteria terlebih dahulu sebelum menginput alternatif.</p>
                <a href="{{ route('perbandingan-kriteria.index.admin') }}" class="btn btn-warning mt-3">
                    Perbaiki Perbandingan Kriteria
                </a>
            </div>
        @endif
    </div>
@endsection