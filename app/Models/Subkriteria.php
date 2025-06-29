<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subkriteria extends Model
{
    use HasFactory;

    protected $table = 'subkriteria';

    protected $fillable = [
        'nama_subkriteria',
        'nilai',
        'kriteria_id',
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function nilai_alternatif()
    {
        return $this->hasMany(NilaiAlternatif::class, 'subkriteria_id');
    }
}
