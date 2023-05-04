<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KehadiranKegnas extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function monitoringKegnas()
    {
        return $this->belongsTo(MonitoringKegnas::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
