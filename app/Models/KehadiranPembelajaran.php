<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranPembelajaran extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function monitoringPembelajaran()
    {
        return $this->belongsTo(MonitoringPembelajaran::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
