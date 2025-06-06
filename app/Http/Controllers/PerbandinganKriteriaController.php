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

    public function hitungHasilPerbandingan(){
        $kriteria = Kriteria::all();
        $perbandingan = PerbandinganKriteria::all();
        $kriteriaIds = Kriteria::pluck('id')->toArray();
        $n = count($kriteriaIds);
        
        $matrix  = [];

        foreach ($kriteriaIds as $rowId) {
            foreach ($kriteriaIds as $colId) {
                $matrix[$rowId][$colId] = $perbandingan->where('kriteria1_id', $rowId)->where('kriteria2_id', $colId)->first()->nilai;
            }
        }

        $eigenResult = $this->calculateEigenVector($matrix, $kriteriaIds);
        $normalized = $eigenResult['normalized'];
        $eigen_vector = $eigenResult['eigen_vector'];

        $consistencyResult = $this->calculateConsistencyRatio($matrix, $eigen_vector, $kriteriaIds);

        $lambda_max = $consistencyResult['lambda_max'];
        $ci = $consistencyResult['ci'];
        $cr = $consistencyResult['cr'];

        return view('Admin.hasil-perbandingan-kriteria.index', compact('kriteria','kriteriaIds','matrix','normalized', 'eigen_vector', 'lambda_max', 'ci', 'cr'));
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

        $matrix = $this->buildComparisonMatrix($kriteriaIds, $nilaiInput, $arahInput);

        // $eigenResult = $this->calculateEigenVector($matrix, $kriteriaIds);
        // $normalized = $eigenResult['normalized'];
        // $eigen_vector = $eigenResult['eigen_vector'];

        // $consistencyResult = $this->calculateConsistencyRatio($matrix, $eigen_vector, $kriteriaIds);
        // $lambda_max = $consistencyResult['lambda_max'];
        // $ci = $consistencyResult['ci'];
        // $cr = $consistencyResult['cr'];

        foreach ($kriteriaIds as $id1) {
            foreach ($kriteriaIds as $id2) {
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

        $kriteriaList = Kriteria::whereIn('id', $kriteriaIds)->pluck('nama_kriteria', 'id')->toArray();
        $kriteria = Kriteria::all();
        $perbandingan = PerbandinganKriteria::all();

        return view('Admin.perbandingan-kriteria.index', compact('kriteria', 'perbandingan', 'kriteriaList'))->with('success', 'Perbandingan kriteria berhasil disimpan.');
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

    function buildComparisonMatrix(array $kriteriaIds, array $nilaiInput, array $arahInput): array
    {
        $matrix = [];
        
        foreach ($kriteriaIds as $id1) {
            foreach ($kriteriaIds as $id2) {
                if ($id1 == $id2) {
                    $matrix[$id1][$id2] = 1.0; // Diagonal = 1
                    continue;
                }

                // Check if comparison exists in A→B or B→A
                $nilai = null;
                $arah = null;

                if (isset($nilaiInput[$id1][$id2]) && isset($arahInput[$id1][$id2])) {
                    $nilai = (float)$nilaiInput[$id1][$id2];
                    $arah = $arahInput[$id1][$id2];
                } elseif (isset($nilaiInput[$id2][$id1]) && isset($arahInput[$id2][$id1])) {
                    $nilai = (float)$nilaiInput[$id2][$id1];
                    $arah = $arahInput[$id2][$id1] == 'AB' ? 'BA' : 'BA';
                }

                // If no data, throw error
                if ($nilai === null || $arah === null) {
                    throw new \Exception("Missing comparison for {$id1} vs {$id2}");
                }

                // Build matrix based on direction
                if ($arah === 'AB') {
                    $matrix[$id1][$id2] = (float)$nilai;
                    $matrix[$id2][$id1] = round(1 / (float)$nilai, 4);
                } else { // BA
                    $matrix[$id2][$id1] = (float)$nilai;
                    $matrix[$id1][$id2] = round(1 / (float)$nilai, 4);
                }
            }
        }
        
        return $matrix;
    }

    /**
     * Calculates the eigen vector from a comparison matrix
     * 
     * @param array $matrix The comparison matrix
     * @param array $kriteriaIds Array of criteria IDs
     * @return array Array with 'normalized' matrix and 'eigen_vector' values
     */
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

    /**
     * Calculates consistency ratio (CR) for AHP
     * 
     * @param array $matrix The comparison matrix
     * @param array $eigen_vector The eigen vector values
     * @param array $kriteriaIds Array of criteria IDs
     * @return array Array with 'lambda_max', 'ci', 'cr' values
     */
    function calculateConsistencyRatio(array $matrix, array $eigen_vector, array $kriteriaIds): array
    {
        $n = count($kriteriaIds);
        
        // Calculate lambda max
        $lambda_max = 0;
        foreach ($kriteriaIds as $i) {
            $weightedSum = 0;
            foreach ($kriteriaIds as $j) {
                $weightedSum += $matrix[$i][$j] * $eigen_vector[$j];
            }
            $lambda_max += $weightedSum / $eigen_vector[$i];
        }
        $lambda_max = $lambda_max / $n;

        // Calculate CI & CR
        $ci = ($lambda_max - $n) / ($n - 1);
        $riTable = [0.00, 0.00, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];
        $ri = $riTable[$n - 1] ?? 1.49;
        $cr = ($ri == 0) ? 0 : $ci / $ri;

        return [
            'lambda_max' => $lambda_max,
            'ci' => $ci,
            'cr' => $cr
        ];
    }

    
}
