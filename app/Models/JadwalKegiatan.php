<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKegiatan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class);
    }

    public function monitoringKegnas()
    {
        return $this->hasMany(MonitoringKegnas::class);
    }

    public function monitoringKegiatan()
    {
        return $this->hasMany(MonitoringKegiatan::class);
    }
}
