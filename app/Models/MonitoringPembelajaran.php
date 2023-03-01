<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringPembelajaran extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function jadwalPelajaran()
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_piket_id');
    }

    public function kehadiranPembelajarans()
    {
        return $this->hasMany(KehadiranPembelajaran::class);
    }
}
