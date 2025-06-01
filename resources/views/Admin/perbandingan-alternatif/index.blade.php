@extends('layouts.adminLayout')

@section('content')
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="modal-title text-gray-900">Perbandingan Alternatif untuk Kriteria: {{ $kriteria->nama_kriteria }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('perbandingan-alternatif.store') }}">
                    @csrf
                    <input type="hidden" name="kriteria_id" value="{{ $kriteria->id }}">

                    <!-- Konten akan diisi oleh JavaScript berdasarkan pilihan metode -->
                    <div id="containerPerbandingan">
                        <!-- Default: Tampilan per pasangan -->
                        <div class="pasangan-container">
                            @foreach($alternatifs as $alt1)
                                @foreach($alternatifs as $alt2)
                                    @if($alt1->id < $alt2->id)
                                        <div class="row align-items-center mb-3 p-3 border rounded">
                                            <div class="col-md-4 text-gray-900">
                                                <strong>{{ $alt1->nama_alternatif }}</strong> vs 
                                                <strong>{{ $alt2->nama_alternatif }}</strong>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <select name="arah[{{ $alt1->id }}][{{ $alt2->id }}]" class="form-control">
                                                    <option value="AB">{{ $alt1->nama_alternatif }} lebih baik</option>
                                                    <option value="BA">{{ $alt2->nama_alternatif }} lebih baik</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <select name="nilai[{{ $alt1->id }}][{{ $alt2->id }}]" class="form-control">
                                                    @for($i = 1; $i <= 9; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-save"></i> Simpan Perbandingan
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection