<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class PerhitunganKeputusanController extends Controller
{
    public function index()
    {
        $ahp = (new PerbandinganKriteriaController)->hitungAHP();
        $eigen_vector = $ahp['eigen_vector'];

        $kriteria = Kriteria::all();
        $tahunAjarans = Alternatif::distinct()->pluck('tahun_ajaran');

        $data = [];

        foreach ($tahunAjarans as $tahun) {
            $alternatifs = Alternatif::with(['nilai_alternatif.subkriteria'])
                ->where('tahun_ajaran', $tahun)
                ->get();

            // Matriks Keputusan
            $matrix = [];
            foreach ($alternatifs as $alt) {
                foreach ($alt->nilai_alternatif as $nilai) {
                    $matrix[$alt->id][$nilai->kriteria_id] = $nilai->subkriteria->nilai ?? 0;
                }
            }

            // Max/Min
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

            // Normalisasi
            $normalisasi = [];
            foreach ($alternatifs as $alt) {
                foreach ($kriteria as $k) {
                    $nilai = $matrix[$alt->id][$k->id] ?? 0;
                    if ($k->tipe_kriteria === 'benefit') {
                        $r = $max[$k->id] > 0 ? $nilai / $max[$k->id] : 0;
                    } else {
                        $r = $nilai > 0 ? $min[$k->id] / $nilai : 0;
                    }
                    $normalisasi[$alt->id][$k->id] = $r;
                }
            }

            // Preferensi
            $hasil = [];
            foreach ($alternatifs as $alt) {
                $total = 0;
                foreach ($kriteria as $k) {
                    $bobot = $eigen_vector[$k->id] ?? 0;
                    $r = $normalisasi[$alt->id][$k->id] ?? 0;
                    $bobot = $eigen_vector[$k->id] ?? 0;

                    $detail[$k->id] = [
                        'normalisasi' => $r,
                        'bobot' => $bobot,
                        'hasil' => $r * $bobot,
                    ];
                    $total += $bobot * $r;
                }
                $hasil[] = [
                    'nama' => $alt->nama_siswa,
                    'preferensi' => $total,
                    'detail' => $detail,
                ];
            }

            usort($hasil, fn ($a, $b) => $b['preferensi'] <=> $a['preferensi']);

            $data[$tahun] = [
                'alternatifs' => $alternatifs,
                'matrix' => $matrix,
                'max' => $max,
                'min' => $min,
                'normalisasi' => $normalisasi,
                'ranking' => $hasil,
            ];
        }

        return view('Admin.perhitungan-keputusan.index', [
            'kriteria' => $kriteria,
            'eigen_vector' => $eigen_vector,
            'tahunAjarans' => $tahunAjarans,
            'data' => $data,
        ]);
    }


}
