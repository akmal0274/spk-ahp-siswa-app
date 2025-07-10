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
        // Hitung bobot AHP
        $ahp = (new PerbandinganKriteriaController)->hitungAHP();
        $eigen_vector = $ahp['eigen_vector'];

        $kriteria = Kriteria::all();
        $tahunAjarans = Alternatif::distinct()->pluck('tahun_ajaran');

        $ranking = [];
        $validasi = [];

        // Filter by tahun ajaran (kalau ada)
        $listTahun = $request->filled('tahun_ajaran') ? [$request->tahun_ajaran] : $tahunAjarans;

        foreach ($listTahun as $tahun) {
            $alternatifs = Alternatif::with(['nilai_alternatif.subkriteria'])
                ->where('tahun_ajaran', $tahun)
                ->get();

            if ($alternatifs->isEmpty()) {
                continue;
            }

            // Ambil status validasi dari salah satu alternatif di tahun tsb
            $validasi[$tahun] = $alternatifs->first()->is_valid ?? 0;

            // Buat matriks nilai
            $matrix = [];
            foreach ($alternatifs as $alt) {
                foreach ($alt->nilai_alternatif as $nilai) {
                    $matrix[$alt->id][$nilai->kriteria_id] = $nilai->subkriteria->nilai ?? 0;
                }
            }

            // Max-Min per kriteria
            $max = [];
            $min = [];
            foreach ($kriteria as $k) {
                $nilai_kriteria = [];
                foreach ($alternatifs as $alt) {
                    $nilai = $matrix[$alt->id][$k->id] ?? 0;
                    $nilai_kriteria[] = $nilai;
                }
                $max[$k->id] = max($nilai_kriteria);
                $min[$k->id] = min($nilai_kriteria);
            }

            // Hitung preferensi
            $result = [];
            foreach ($alternatifs as $alt) {
                $total = 0;
                foreach ($kriteria as $k) {
                    $nilai = $matrix[$alt->id][$k->id] ?? 0;

                    if ($k->tipe_kriteria === 'benefit') {
                        $r = $max[$k->id] > 0 ? $nilai / $max[$k->id] : 0;
                    } else {
                        $r = $nilai > 0 ? $min[$k->id] / $nilai : 0;
                    }

                    $bobot = $eigen_vector[$k->id] ?? 0;

                    $detail[$k->id] = [
                        'normalisasi' => $r,
                        'bobot' => $bobot,
                        'hasil' => $r * $bobot,
                    ];

                    $total += $bobot * $r;
                }

                $result[] = [
                    'alternatif' => $alt,
                    'skor' => $total,
                    'detail' => $detail
                ];
            }

            usort($result, fn ($a, $b) => $b['skor'] <=> $a['skor']);

            $ranking[$tahun] = $result;
        }

        // === Jika USER (bukan admin), filter status validasi ===
        if (Auth::user()->role !== 'admin') {
            $status = $request->status_validasi;
            if ($status === 'Sudah') {
                $ranking = collect($ranking)->filter(fn($rows, $tahun) => ($validasi[$tahun] ?? 0) == 1)->toArray();
            } elseif ($status === 'Belum') {
                $ranking = collect($ranking)->filter(fn($rows, $tahun) => ($validasi[$tahun] ?? 0) == 0)->toArray();
            }
        }

        // RETURN VIEW
        if (Auth::user()->role === 'admin') {
            return view('Admin.ranking-akhir.index', compact('ranking', 'tahunAjarans', 'validasi'));
        } else {
            return view('ranking-akhir', compact('ranking', 'tahunAjarans', 'validasi'));
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

        $kriteria = Kriteria::all();

        if ($request->filled('tahun_ajaran')) {
            $tahunAjarans = [$request->tahun_ajaran];
        } else {
            $tahunAjarans = Alternatif::distinct()->pluck('tahun_ajaran');
        }

        $spreadsheet = new Spreadsheet();
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

            // Buat matriks
            $matrix = [];
            foreach ($alternatifs as $alt) {
                foreach ($alt->nilai_alternatif as $nilai) {
                    $matrix[$alt->id][$nilai->kriteria_id] = $nilai->subkriteria->nilai ?? 0;
                }
            }

            // Hitung max/min
            $max = [];
            $min = [];
            foreach ($kriteria as $k) {
                $nilai_kriteria = [];
                foreach ($alternatifs as $alt) {
                    $nilai = $matrix[$alt->id][$k->id] ?? 0;
                    $nilai_kriteria[] = $nilai;
                }
                $max[$k->id] = max($nilai_kriteria);
                $min[$k->id] = min($nilai_kriteria);
            }

            // Hitung preferensi
            $result = [];
            foreach ($alternatifs as $alt) {
                $total = 0;
                foreach ($kriteria as $k) {
                    $nilai = $matrix[$alt->id][$k->id] ?? 0;

                    if ($k->tipe_kriteria === 'benefit') {
                        $r = $max[$k->id] > 0 ? $nilai / $max[$k->id] : 0;
                    } else {
                        $r = $nilai > 0 ? $min[$k->id] / $nilai : 0;
                    }

                    $bobot = $eigen_vector[$k->id] ?? 0;

                    $detail[$k->id] = [
                        'normalisasi' => $r,
                        'bobot' => $bobot,
                        'hasil' => $r * $bobot,
                    ];

                    $total += $bobot * $r;
                }

                $result[] = [
                    'alternatif' => $alt,
                    'skor' => $total,
                    'detail' => $detail
                ];
            }

            usort($result, fn($a, $b) => $b['skor'] <=> $a['skor']);

            // Tulis ke Excel
            $sheet->setCellValue('A1', 'Peringkat');
            $sheet->setCellValue('B1', 'Nama Alternatif');
            $sheet->setCellValue('C1', 'Skor Akhir');

            $row = 2;
            foreach ($result as $i => $r) {
                $sheet->setCellValue('A' . $row, $i + 1);
                $sheet->setCellValue('B' . $row, $r['alternatif']->nama ?? $r['alternatif']->nama_siswa ?? '-');
                $sheet->setCellValue('C' . $row, round($r['skor'], 4));
                $row++;
            }

            $sheetIndex++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'hasil_pemilihan_siswa.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }

    public function validasi(Request $request)
    {
        Alternatif::where('tahun_ajaran', $request->tahun_ajaran)->update(['is_valid' => true]);

        return redirect()->route('ranking-akhir.index.admin')->with('success', 'Data berhasil disimpan');
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
