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
    public function index(Request $request)
    {
        $ahp = (new PerbandinganKriteriaController)->hitungAHP();
        $eigen_vector = $ahp['eigen_vector'];

        // Ambil semua tahun ajaran untuk dropdown
        $tahunAjarans = Alternatif::distinct()->pluck('tahun_ajaran');

        $ranking = [];

        // Jika user pilih filter tahun ajaran
        if ($request->filled('tahun_ajaran')) {
            $tahun = $request->tahun_ajaran;

            $alternatifs = Alternatif::with(['nilai_alternatif.subkriteria'])
                ->where('tahun_ajaran', $tahun)
                ->get();

            $result = [];

            foreach ($alternatifs as $alt) {
                $total = 0;
                foreach ($alt->nilai_alternatif as $nilai) {
                    $bobot_kriteria = $eigen_vector[$nilai->kriteria_id] ?? 0;
                    $bobot_subkriteria = $nilai->subkriteria->nilai ?? 0;
                    $total += $bobot_kriteria * $bobot_subkriteria;
                }

                $result[] = [
                    'alternatif' => $alt,
                    'skor' => $total,
                ];
            }

            usort($result, fn ($a, $b) => $b['skor'] <=> $a['skor']);

            $ranking[$tahun] = $result;

        } else {
            // Tidak ada filter → hitung semua tahun ajaran
            foreach ($tahunAjarans as $tahun) {
                $alternatifs = Alternatif::with(['nilai_alternatif.subkriteria'])
                    ->where('tahun_ajaran', $tahun)
                    ->get();

                $result = [];

                foreach ($alternatifs as $alt) {
                    $total = 0;
                    foreach ($alt->nilai_alternatif as $nilai) {
                        $bobot_kriteria = $eigen_vector[$nilai->kriteria_id] ?? 0;
                        $bobot_subkriteria = $nilai->subkriteria->nilai ?? 0;
                        $total += $bobot_kriteria * $bobot_subkriteria;
                    }

                    $result[] = [
                        'alternatif' => $alt,
                        'skor' => $total,
                    ];
                }

                usort($result, fn ($a, $b) => $b['skor'] <=> $a['skor']);

                $ranking[$tahun] = $result;
            }
        }

        if (Auth::user()->role === 'admin') {
            return view('Admin.ranking-akhir.index', compact('ranking', 'tahunAjarans'));
        } else {
            return view('ranking-akhir', compact('ranking', 'tahunAjarans'));
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

    public function exportExcel(Request $request)
    {
        $ahp = (new PerbandinganKriteriaController)->hitungAHP();
        $eigen_vector = $ahp['eigen_vector'];

        if ($request->filled('tahun_ajaran')) {
            $tahunAjarans = [$request->tahun_ajaran];
        } else {
            $tahunAjarans = Alternatif::distinct()->pluck('tahun_ajaran');
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheetIndex = 0;

        foreach ($tahunAjarans as $tahun) {
            if ($sheetIndex == 0) {
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle($tahun);
            } else {
                $sheet = $spreadsheet->createSheet();
                $sheet->setTitle($tahun);
            }

            $alternatifs = Alternatif::with(['nilai_alternatif.subkriteria'])
                ->where('tahun_ajaran', $tahun)
                ->get();

            $result = [];

            foreach ($alternatifs as $alt) {
                $total = 0;
                foreach ($alt->nilai_alternatif as $nilai) {
                    $bobot_kriteria = $eigen_vector[$nilai->kriteria_id] ?? 0;
                    $bobot_subkriteria = $nilai->subkriteria->nilai ?? 0;
                    $total += $bobot_kriteria * $bobot_subkriteria;
                }

                $result[] = [
                    'alternatif' => $alt,
                    'skor' => $total,
                ];
            }

            usort($result, fn ($a, $b) => $b['skor'] <=> $a['skor']);

            $sheet->setCellValue('A1', 'Peringkat');
            $sheet->setCellValue('B1', 'Nama Siswa');
            $sheet->setCellValue('C1', 'Skor Akhir');

            $row = 2;
            foreach ($result as $i => $r) {
                $sheet->setCellValue('A' . $row, $i + 1);
                $sheet->setCellValue('B' . $row, $r['alternatif']->nama_siswa);
                $sheet->setCellValue('C' . $row, number_format($r['skor'], 4));
                $row++;
            }

            $sheetIndex++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
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
