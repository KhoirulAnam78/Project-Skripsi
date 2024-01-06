<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringPembelajaranNew extends Model
{
    use HasFactory;
    protected $primaryKey = 'monitoring_pembelajaran_id';
    protected $guarded = ['monitoring_pembelajaran_id'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function guruPiket()
    {
        return $this->belongsTo(Guru::class, 'guru_piket_id');
    }

    public function kehadiranPembelajarans()
    {
        return $this->hasMany(KehadiranPembelajaran::class);
    }
}
