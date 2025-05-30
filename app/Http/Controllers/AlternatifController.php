<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alternatif;
use Illuminate\Support\Facades\DB;

class AlternatifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alternatif = Alternatif::all();

        return view('Admin.alternatif.index', compact('alternatif'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.alternatif.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $alternatifValidated = $request->validate([
            'nis' => 'required|unique:alternatif,nis',
            'nama_siswa' => 'required',
            'kelas' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $alternatif = Alternatif::create($alternatifValidated);

            DB::commit();
            return redirect()->route('alternatif.index.admin')->with('success', 'Data alternatif berhasil ditambahkan');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('alternatif.index.admin')->with('error', 'Data alternatif gagal ditambahkan');
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
    public function destroy(string $id)
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
