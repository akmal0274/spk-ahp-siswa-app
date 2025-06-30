<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Subkriteria;
use Illuminate\Http\Request;

class SubkriteriaController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::all();
        $subkriteria = Subkriteria::all();

        $nilai_konsistensi = (new PerbandinganKriteriaController)->hitungAHP();

        $cr = $nilai_konsistensi['cr'];

        return view('Admin.subkriteria.index', compact('kriteria', 'subkriteria', 'cr'));
    }

    public function create($id)
    {
        $kriteria = Kriteria::find($id);
        return view('Admin.subkriteria.create', compact('kriteria'));
    }

    public function store(Request $request)
    {

        $subkriteria = Subkriteria::create([
            'nama_subkriteria' => $request->nama_subkriteria,
            'nilai' => $request->nilai_subkriteria,
            'kriteria_id' => $request->kriteria_id
        ]);
        return redirect()->route('subkriteria.index.admin')->with('status', 'Subkriteria Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $subkriteria = Subkriteria::find($id);
        return view('Admin.subkriteria.edit', compact('subkriteria'));
    }

    public function update(Request $request, $id)
    {
        $subkriteria = Subkriteria::find($id);
        $subkriteria->update([
            'nama_subkriteria' => $request->nama_subkriteria,
            'nilai' => $request->nilai_subkriteria,
        ]);
        return redirect()->route('subkriteria.index.admin')->with('status', 'Subkriteria Berhasil Diubah');
    }

    public function destroy($id)
    {
        $subkriteria = Subkriteria::find($id);
        $subkriteria->delete();
        return redirect()->route('subkriteria.index.admin')->with('status', 'Subkriteria Berhasil Dihapus');
    }
}
