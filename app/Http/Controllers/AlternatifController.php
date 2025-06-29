<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\NilaiAlternatif;
use App\Models\Subkriteria;
use Illuminate\Support\Facades\DB;

class AlternatifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alternatif = Alternatif::with(['nilai_alternatif.subkriteria'])->get();
        $kriteria = Kriteria::all();

        return view('Admin.alternatif.index', compact('alternatif', 'kriteria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kriteria = Kriteria::all();
        $subkriteria = Subkriteria::all();
        return view('Admin.alternatif.create', compact('kriteria', 'subkriteria'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1️⃣ Validasi data dasar
        $request->validate([
            'nis' => 'required|unique:alternatif,nis',
            'nama_siswa' => 'required',
            'kelas' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'tahun_ajaran' => 'required',
            'kriteria' => 'required|array',
            'tingkat_lomba' => 'required|integer',
            'peringkat_lomba' => 'required|integer',
        ]);

        // 2️⃣ Simpan data alternatif
        $alternatif = Alternatif::create([
            'nis' => $request->nis,
            'nama_siswa' => $request->nama_siswa,
            'kelas' => $request->kelas,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        // 3️⃣ Simpan nilai kriteria (selain Prestasi)
        foreach ($request->kriteria as $kriteria_id => $subkriteria_id) {
            NilaiAlternatif::create([
                'alternatif_id' => $alternatif->id,
                'kriteria_id' => $kriteria_id,
                'subkriteria_id' => $subkriteria_id,
            ]);
        }

        $tingkat_lomba = $request->tingkat_lomba;
        $peringkat_lomba = $request->peringkat_lomba;

        $prestasi = (int)$tingkat_lomba + (int)$peringkat_lomba;

        if($prestasi == 7){
            $subkriteria = Subkriteria::where('nama_subkriteria', 'Sangat Unggul (Skor = 7)')->first();
            NilaiAlternatif::create([
                'alternatif_id' => $alternatif->id,
                'kriteria_id' => $subkriteria->kriteria_id,
                'subkriteria_id' => $subkriteria->id,
            ]);
        }else if ($prestasi>=5 && $prestasi<=6){ 
            $subkriteria = Subkriteria::where('nama_subkriteria', 'Unggul (Skor = 5-6)')->first();
            NilaiAlternatif::create([
                'alternatif_id' => $alternatif->id,
                'kriteria_id' => $subkriteria->kriteria_id,
                'subkriteria_id' => $subkriteria->id,
            ]);
        }else if ($prestasi>=3 && $prestasi<=4){ 
            $subkriteria = Subkriteria::where('nama_subkriteria', 'Cukup Layak (Skor = 3-4)')->first();
            NilaiAlternatif::create([
                'alternatif_id' => $alternatif->id,
                'kriteria_id' => $subkriteria->kriteria_id,
                'subkriteria_id' => $subkriteria->id,
            ]);
        }else{
            $subkriteria = Subkriteria::where('nama_subkriteria', 'Belum Memadai (Skor = 1-2)')->first();
            NilaiAlternatif::create([
                'alternatif_id' => $alternatif->id,
                'kriteria_id' => $subkriteria->kriteria_id,
                'subkriteria_id' => $subkriteria->id,
            ]);
        }

        return redirect()->route('alternatif.index.admin')
                        ->with('success', 'Data alternatif berhasil disimpan.');
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
        $alternatif = Alternatif::find($id);
        return view('Admin.alternatif.edit', compact('alternatif'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $alternatifValidated = $request->validate([
            'nis' => 'required|exists:alternatif,nis',
            'nama_siswa' => 'required',
            'kelas' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $alternatif = Alternatif::find($id);
            $alternatif->update($alternatifValidated);

            DB::commit();
            return redirect()->route('alternatif.index.admin')->with('success', 'Data alternatif berhasil diupdate');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('alternatif.index.admin')->with('error', 'Data alternatif gagal diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $alternatif = Alternatif::find($id);
            $alternatif->delete();

            DB::commit();
            return redirect()->route('alternatif.index.admin')->with('success', 'Data alternatif berhasil dihapus');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('alternatif.index.admin')->with('error', 'Data alternatif gagal dihapus');
        }
    }
}
