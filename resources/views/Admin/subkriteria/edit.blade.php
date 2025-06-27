@extends('layouts.adminLayout')

@section('content')
<div class="col-md-12">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="modal-title text-gray-900">
                Edit Subkriteria - {{ $subkriteria->kriteria->nama_kriteria }}
            </h5>
            <a href="{{ route('subkriteria.index.admin') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('subkriteria.update.admin', $subkriteria->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group col-md-6 text-gray-900">
                        <label for="nama_subkriteria">Nama Subkriteria</label>
                        <input
                            type="text"
                            id="nama_subkriteria"
                            name="nama_subkriteria"
                            class="form-control"
                            value="{{ old('nama_subkriteria', $subkriteria->nama_subkriteria) }}"
                            required
                        >
                    </div>
                    <div class="form-group col-md-6 text-gray-900">
                        <label for="nilai_subkriteria">Nilai Subkriteria</label>
                        <select id="nilai_subkriteria" name="nilai_subkriteria" class="form-control" required>
                            <option value="">-- Pilih Nilai --</option>
                            @for ($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}" {{ $subkriteria->nilai == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
