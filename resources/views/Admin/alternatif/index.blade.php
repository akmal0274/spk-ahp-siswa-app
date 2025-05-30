@extends('layouts.adminLayout')

@section('content')
    <div class="col-md-12">
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
                                <td>
                                    <a href="{{ route('alternatif.edit.admin', $a->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('alternatif.destroy.admin',$a->id) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger " onclick="return confirm('Apakah anda yakin untuk menghapus project ini?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data alternatif</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection