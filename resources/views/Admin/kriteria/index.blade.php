@extends('layouts.adminLayout')
    
@section('content')
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title text-gray-900">Data Kriteria</h5>
                <a href="{{ route('kriteria.create.admin') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Kriteria
                </a>
            </div>   
            
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Kriteria</th>
                            <th>Kriteria</th>
                            <th>Tipe Kriteria</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($kriteria) > 0)
                            @foreach ($kriteria as $k)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $k->kode_kriteria }}</td>
                                <td>{{ $k->nama_kriteria }}</td>
                                <td>{{ ucfirst($k->tipe_kriteria) }}</td>
                                <td>
                                    <a href="{{ route('kriteria.edit.admin', $k->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('kriteria.destroy.admin',$k->id) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger " onclick="return confirm('Apakah anda yakin untuk menghapus project ini?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data kriteria</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection