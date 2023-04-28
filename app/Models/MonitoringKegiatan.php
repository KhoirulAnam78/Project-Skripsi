<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringKegiatan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function jadwalKegiatan()
    {
        return $this->belongsTo(JadwalKegiatan::class);
    }

    public function kehadiranKegiatan()
    {
        return $this->hasMany(KehadiranKegiatan::class);
    }
}
