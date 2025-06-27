<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\PerbandinganKriteria;
use App\Models\PerbandinganAlternatif;
use App\Models\Alternatif;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RankingAkhirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kriteria = Kriteria::all();
        $perbandingan = PerbandinganKriteria::all();
        $kriteriaIds = Kriteria::pluck('id')->toArray();
        $alternatif = Alternatif::all();

        $matrix_kriteria = [];

        foreach ($kriteriaIds as $rowId) {
            foreach ($kriteriaIds as $colId) {
                $matrix_kriteria[$rowId][$colId] = $perbandingan->where('kriteria1_id', $rowId)->where('kriteria2_id', $colId)->first()->nilai;
            }
        }

        $eigen_kriteria = $this->calculateEigenVector($matrix_kriteria, $kriteriaIds);
        $bobotKriteria = $eigen_kriteria['eigen_vector'];

        $nilaiAkhir = [];
        foreach ($alternatif as $alt) {
            $total = 0;
            foreach ($kriteria as $k) {
                $rel = PerbandinganAlternatif::where('kriteria_id', $k->id)->get();
                $altIds = $alternatif->pluck('id')->toArray();

                // Bangun matriks alternatif untuk kriteria ini
                $matrix = [];
                foreach ($altIds as $i) {
                    foreach ($altIds as $j) {
                        if ($i == $j) {
                            $matrix[$i][$j] = 1;
                        } else {
                            // Ambil nilai langsung
                            $nilaiLangsung = $rel->first(function ($item) use ($i, $j) {
                                return $item->alternatif1_id == $i && $item->alternatif2_id == $j;
                            });

                            // Jika tidak ada, ambil nilai kebalikannya
                            if ($nilaiLangsung) {
                                $matrix[$i][$j] = $nilaiLangsung->nilai;
                            } else {
                                $nilaiKebalikan = $rel->first(function ($item) use ($i, $j) {
                                    return $item->alternatif1_id == $j && $item->alternatif2_id == $i;
                                });

                                $matrix[$i][$j] = $nilaiKebalikan ? 1 / $nilaiKebalikan->nilai : 1;
                            }
                        }
                    }
                }

                $eigenAlt = $this->calculateEigenVector($matrix, $altIds);
                $bobotAlternatif = $eigenAlt['eigen_vector'];

                $total += $bobotKriteria[$k->id] * ($bobotAlternatif[$alt->id] ?? 0);
            }

            $nilaiAkhir[$alt->id] = $total;
        }

        $user = Auth::user();
        if ($user->role === 'admin') {
            return view('Admin.ranking-akhir.index', compact('alternatif', 'nilaiAkhir'));
        }
        else {
            return view('ranking-akhir', compact('alternatif', 'nilaiAkhir'));
        }

    }

    function calculateEigenVector(array $matrix, array $kriteriaIds): array
    {
        $n = count($kriteriaIds);
        $columnSums = [];
        
        // Calculate column sums
        foreach ($kriteriaIds as $j) {
            $columnSums[$j] = 0;
            foreach ($kriteriaIds as $i) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        // Normalize and calculate eigen vector
        $normalized = [];
        $eigen_vector = [];
        
        foreach ($kriteriaIds as $i) {
            $sumRow = 0;
            foreach ($kriteriaIds as $j) {
                $normalized[$i][$j] = $matrix[$i][$j] / $columnSums[$j];
                $sumRow += $normalized[$i][$j];
            }
            $eigen_vector[$i] = round($sumRow / $n, 4);
        }
        
        return [
            'normalized' => $normalized,
            'eigen_vector' => $eigen_vector
        ];
    }

    public function exportExcel(){
        $kriteria = Kriteria::all();
        $perbandingan = PerbandinganKriteria::all();
        $kriteriaIds = Kriteria::pluck('id')->toArray();
        $alternatif = Alternatif::all();

        $matrix_kriteria = [];

        foreach ($kriteriaIds as $rowId) {
            foreach ($kriteriaIds as $colId) {
                $matrix_kriteria[$rowId][$colId] = $perbandingan->where('kriteria1_id', $rowId)->where('kriteria2_id', $colId)->first()->nilai;
            }
        }

        $eigen_kriteria = $this->calculateEigenVector($matrix_kriteria, $kriteriaIds);
        $bobotKriteria = $eigen_kriteria['eigen_vector'];

        $nilaiAkhir = [];
        foreach ($alternatif as $alt) {
            $total = 0;
            foreach ($kriteria as $k) {
                $rel = PerbandinganAlternatif::where('kriteria_id', $k->id)->get();
                $altIds = $alternatif->pluck('id')->toArray();

                // Bangun matriks alternatif untuk kriteria ini
                $matrix = [];
                foreach ($altIds as $i) {
                    foreach ($altIds as $j) {
                        if ($i == $j) {
                            $matrix[$i][$j] = 1;
                        } else {
                            // Ambil nilai langsung
                            $nilaiLangsung = $rel->first(function ($item) use ($i, $j) {
                                return $item->alternatif1_id == $i && $item->alternatif2_id == $j;
                            });

                            // Jika tidak ada, ambil nilai kebalikannya
                            if ($nilaiLangsung) {
                                $matrix[$i][$j] = $nilaiLangsung->nilai;
                            } else {
                                $nilaiKebalikan = $rel->first(function ($item) use ($i, $j) {
                                    return $item->alternatif1_id == $j && $item->alternatif2_id == $i;
                                });

                                $matrix[$i][$j] = $nilaiKebalikan ? 1 / $nilaiKebalikan->nilai : 1;
                            }
                        }
                    }
                }

                $eigenAlt = $this->calculateEigenVector($matrix, $altIds);
                $bobotAlternatif = $eigenAlt['eigen_vector'];

                $total += $bobotKriteria[$k->id] * ($bobotAlternatif[$alt->id] ?? 0);
            }

            $nilaiAkhir[$alt->id] = $total;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Peringkat');
        $sheet->setCellValue('B1', 'Nama Siswa');
        $sheet->setCellValue('C1', 'Skor Akhir');

        // Data
        $ranked = collect($nilaiAkhir)->sortDesc();
        $row = 2;
        $peringkat = 1;
        foreach ($ranked as $alt_id => $skor) {
            $sheet->setCellValue('A' . $row, $peringkat++);
            $sheet->setCellValue('B' . $row, $alternatif->find($alt_id)->nama_siswa ?? 'Unknown');
            $sheet->setCellValue('C' . $row, number_format($skor, 4));
            $row++;
        }

        // Auto download
        $writer = new Xlsx($spreadsheet);
        $filename = 'hasil_pemilihan_siswa.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
