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
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center">Kriteria</th>
                                    @foreach($kriteria as $k)
                                        <th class="text-center">{{ $k->nama_kriteria }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kriteria as $k1)
                                    <tr>
                                        <td class="font-weight-bold bg-light">
                                            {{ $k1->nama_kriteria }}
                                        </td>
                                        @foreach($kriteria as $k2)
                                            <td class="text-center align-middle">
                                                @if($k1->id == $k2->id)
                                                    <input type="text" 
                                                        class="form-control text-center bg-white" 
                                                        value="1" 
                                                        readonly>
                                                @elseif($k1->id < $k2->id)
                                                    @php
                                                        // Cara lebih reliable untuk get existing value
                                                        $existing = $perbandingan->filter(function($item) use ($k1, $k2) {
                                                            return $item->kriteria1_id == $k1->id && 
                                                                $item->kriteria2_id == $k2->id;
                                                        })->first();
                                                    @endphp
                                                    <select name="perbandingan[{{ $k1->id }}][{{ $k2->id }}]" 
                                                            class="form-control select-perbandingan"
                                                            data-row="{{ $k1->id }}" 
                                                            data-col="{{ $k2->id }}">
                                                        @for($i = 1; $i <= 9; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ ($existing && $existing->nilai == $i) ? 'selected' : '' }}>
                                                                {{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                @else
                                                    @php
                                                        $inverse = $perbandingan->filter(function($item) use ($k1, $k2) {
                                                            return $item->kriteria1_id == $k2->id && 
                                                                $item->kriteria2_id == $k1->id;
                                                        })->first();
                                                    @endphp
                                                    <input type="text" 
                                                        class="form-control text-center bg-light" 
                                                        value="{{ $inverse ? number_format($inverse->nilai, 2) : '' }}" 
                                                        readonly
                                                        data-row="{{ $k2->id }}" 
                                                        data-col="{{ $k1->id }}">
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <div class="alert alert-info">
                            <strong>Petunjuk Pengisian:</strong> 
                            <ul class="mb-0">
                                <li>Skala 1-9 (1 = Sama penting, 9 = Sangat lebih penting)</li>
                                <li>Nilai pecahan (1/2, 1/3, dst.) akan otomatis terisi</li>
                            </ul>
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
    <script>
    $(document).ready(function() {
        // Gunakan jQuery untuk kompatibilitas lebih baik
        $('body').on('change', '.select-perbandingan', function() {
            const row = $(this).data('row');
            const col = $(this).data('col');
            const value = parseFloat($(this).val());
            
            if (value > 0) {
                const inverseValue = (1/value).toFixed(2);
                $(`input[data-row="${col}"][data-col="${row}"]`).val(inverseValue);
            }
        });
    });
    </script>
@endsection