@extends('layouts.adminLayout')

@section('content')
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title text-gray-900">Edit Alternatif</h5>
                <a href="{{ route('alternatif.index.admin') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('alternatif.update.admin', $alternatif->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group text-gray-900">
                        <label for="name">NIS</label>
                        <input type="text" name="nis" class="form-control" value="{{ $alternatif->nis }}" required>
                    </div>
                    <div class="form-group text-gray-900">
                        <label for="name">Nama Siswa</label>
                        <input type="text" name="nama_siswa" class="form-control" value="{{ $alternatif->nama_siswa }}" required>
                    </div>
                    <div class="form-group text-gray-900">
                        <label for="name">Kelas</label>
                        <input type="text" name="kelas" class="form-control" value="{{ $alternatif->kelas }}" required>
                    </div>
                    <div class="form-group text-gray-900">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="L" {{ $alternatif->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ $alternatif->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection