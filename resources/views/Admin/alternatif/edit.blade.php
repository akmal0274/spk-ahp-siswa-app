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
                        <label for="nis">NIS</label>
                        <input type="text" name="nis" class="form-control" value="{{ $alternatif->nis }}" required>
                    </div>

                    <div class="form-group text-gray-900">
                        <label for="nama_siswa">Nama Siswa</label>
                        <input type="text" name="nama_siswa" class="form-control" value="{{ $alternatif->nama_siswa }}" required>
                    </div>

                    <div class="form-group text-gray-900">
                        <label for="kelas">Kelas</label>
                        <input type="text" name="kelas" class="form-control" value="{{ $alternatif->kelas }}" required>
                    </div>

                    <div class="form-group text-gray-900">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ $alternatif->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ $alternatif->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group text-gray-900">
                        <label for="tahun_ajaran">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" class="form-control" value="{{ $alternatif->tahun_ajaran }}" required>
                    </div>

                    @foreach ($kriteria as $k)
                        @if (!Str::contains(Str::lower($k->nama_kriteria), 'prestasi'))
                            <div class="form-group text-gray-900">
                                <label for="kriteria_{{ $k->id }}">{{ $k->nama_kriteria }}</label>
                                <select name="kriteria[{{ $k->id }}]" class="form-control" required>
                                    <option value="">Pilih Nilai</option>
                                    @php
                                        $subs = $subkriteria->where('kriteria_id', $k->id);
                                        $selectedSub = $alternatif->nilai_alternatif->firstWhere('kriteria_id', $k->id);
                                    @endphp
                                    @foreach ($subs as $sub)
                                        <option value="{{ $sub->id }}"
                                            {{ $selectedSub && $selectedSub->subkriteria_id == $sub->id ? 'selected' : '' }}>
                                            {{ $sub->nama_subkriteria }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    @endforeach

                    <div class="form-group text-gray-900">
                        <label for="tingkat_lomba">Tingkat Lomba</label>
                        <select name="tingkat_lomba" class="form-control" required>
                            <option value="">Pilih Nilai</option>
                            <option value="3">Nasional</option>
                            <option value="2">Kota/Kabupaten</option>
                            <option value="1">Kecamatan</option>
                        </select>
                    </div>

                    <div class="form-group text-gray-900">
                        <label for="peringkat_lomba">Peringkat Lomba</label>
                        <select name="peringkat_lomba" class="form-control" required>
                            <option value="">Pilih Nilai</option>
                            <option value="4">Juara 1</option>
                            <option value="3">Juara 2</option>
                            <option value="2">Juara 3</option>
                            <option value="1">Juara Harapan</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
