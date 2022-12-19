<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwalPelajarans()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function monitoringPembelajarans()
    {
        return $this->hasMany(MonitoringPelajaran::class);
    }

    public function jadwalGuruPikets()
    {
        return $this->hasMany(JadwalGuruPiket::class);
    }
}
