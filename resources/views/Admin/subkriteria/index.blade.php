@extends('layouts.adminLayout')

@section('content')
<div class="col-md-12">
    <h2 class="text-gray-900">
        Data Sub Kriteria
    </h2>
    @foreach ($kriteria as $k)
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title text-gray-900">
                    {{ $k->kode_kriteria }} - {{ $k->nama_kriteria }}
                </h5>
                <a href="{{ route('subkriteria.create.admin', [$k->id]) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Subkriteria
                </a>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Subkriteria</th>
                            <th>Nilai Subkriteria</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @php $subs = $subkriteria->where('kriteria_id', $k->id); @endphp

                        @forelse ($subs as $sub)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $sub->nama_subkriteria }}</td>
                            <td>{{ $sub->nilai }}</td>
                            <td>
                                <a href="{{ route('subkriteria.edit.admin', $sub->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('subkriteria.destroy.admin', $sub->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah anda yakin ingin menghapus subkriteria ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada subkriteria untuk kriteria ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection