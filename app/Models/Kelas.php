<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    public function rombels()
    {
        return $this->hasMany(Rombel::class);
    }

    public function jadwalPelajarans()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }
}
