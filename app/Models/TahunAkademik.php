<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
    public function jadwalKegiatans()
    {
        return $this->hasMany(JadwalKegiatan::class);
    }
}
