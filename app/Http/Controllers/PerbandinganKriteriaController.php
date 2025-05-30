<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\PerbandinganKriteria;

class PerbandinganKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kriteria = Kriteria::all();
        $perbandingan = PerbandinganKriteria::all();

        return view('Admin.perbandingan-kriteria.index', compact('kriteria', 'perbandingan'));
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
        $arahInput = $request->input('arah');   // Array: arah[A][B]
        $nilaiInput = $request->input('nilai'); // Array: nilai[A][B]
        // dd($arahInput, $nilaiInput);

        // $kriteriaIds = array_merge(
        //     array_keys($arahInput),
        //     array_keys($nilaiInput)
        // );

        $kriteriaIds = Kriteria::pluck('id')->toArray();
        $n = count($kriteriaIds);
        // dd($kriteriaIds);

        // Bangun matriks perbandingan dari input
        $matrix = [];

        foreach ($kriteriaIds as $id1) {
            foreach ($kriteriaIds as $id2) {
                if ($id1 == $id2) {
                    $matrix[$id1][$id2] = 1.0; // Diagonal = 1
                    continue;
                }

                // Cek apakah perbandingan ada di A→B atau B→A
                $nilai = null;
                $arah = null;

                if (isset($nilaiInput[$id1][$id2]) && isset($arahInput[$id1][$id2])) {
                    $nilai = (float)$nilaiInput[$id1][$id2];
                    $arah = $arahInput[$id1][$id2];
                } elseif (isset($nilaiInput[$id2][$id1]) && isset($arahInput[$id2][$id1])) {
                    $nilai = (float)$nilaiInput[$id2][$id1];
                    $arah = $arahInput[$id2][$id1] == 'AB' ? 'BA' : 'BA';
                }

                // Jika tidak ada data, beri nilai default atau lempar error
                if ($nilai === null || $arah === null) {
                    throw new \Exception("Missing comparison for {$id1} vs {$id2}");
                }

                // Bangun matriks berdasarkan arah
                if ($arah === 'AB') {
                    $matrix[$id1][$id2] = (float)$nilai;
                    $matrix[$id2][$id1] = round(1 / (float)$nilai, 4);
                } else { // BA
                    $matrix[$id2][$id1] = (float)$nilai;
                    $matrix[$id1][$id2] = round(1 / (float)$nilai, 4);
                }
            }
        }

        // dd($matrix);

        // Hitung eigen vector
        $columnSums = [];
        foreach ($kriteriaIds as $j) {
            $columnSums[$j] = 0;
            foreach ($kriteriaIds as $i) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

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

        // Hitung lambda max
        $lambda_max = 0;
        foreach ($kriteriaIds as $i) {
            $weightedSum = 0;
            foreach ($kriteriaIds as $j) {
                $weightedSum += $matrix[$i][$j] * $eigen_vector[$j];
            }
            $lambda_max += $weightedSum / $eigen_vector[$i];
        }
        $lambda_max = $lambda_max / $n;

        // Hitung CI & CR
        $ci = ($lambda_max - $n) / ($n - 1);
        $riTable = [0.00, 0.00, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];
        $ri = $riTable[$n - 1] ?? 1.49;
        $cr = ($ri == 0) ? 0 : $ci / $ri;

        // Jika konsisten, simpan ke database
        if ($cr < 0.1) {
            foreach ($kriteriaIds as $id1) {
                foreach ($kriteriaIds as $id2) {
                    if ($id1 === $id2) continue;
                    $nilaiFinal = $matrix[$id1][$id2];
                    PerbandinganKriteria::updateOrCreate(
                        [
                            'kriteria1_id' => $id1,
                            'kriteria2_id' => $id2,
                        ],
                        [
                            'nilai' => $nilaiFinal,
                        ]
                    );
                }
            }

            // (Opsional) Simpan bobot kriteria dari eigen vector
            // foreach ($eigen_vector as $id => $bobot) {
            //     BobotKriteria::updateOrCreate(
            //         ['kriteria_id' => $id],
            //         ['bobot' => $bobot]
            //     );
            // }

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Data disimpan karena konsisten.',
            //     'eigen_vector' => $eigen_vector,
            //     'lambda_max' => round($lambda_max, 4),
            //     'ci' => round($ci, 4),
            //     'cr' => round($cr, 4),
            // ]);
            $kriteriaList = Kriteria::whereIn('id', $kriteriaIds)->pluck('nama_kriteria', 'id')->toArray();
            $kriteria = Kriteria::all();
            $perbandingan = PerbandinganKriteria::all();


            return view('Admin.perbandingan-kriteria.index', compact('matrix', 'eigen_vector', 'lambda_max', 'ci', 'cr', 'kriteriaList', 'kriteriaIds', 'kriteria', 'perbandingan'))->with(['success' => true, 'message' => 'Data berhasil disimpan karena konsisten.']);
        } else {
            dd($matrix, $eigen_vector, $lambda_max, $ci, $cr);
            return redirect()->back()->withErrors('Perbandingan tidak konsisten. Harap periksa kembali input.');
        }

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
