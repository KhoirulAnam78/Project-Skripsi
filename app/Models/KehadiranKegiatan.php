<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranKegiatan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function monitoringKegiatan()
    {
        return $this->belongsTo(MonitoringKegiatan::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
