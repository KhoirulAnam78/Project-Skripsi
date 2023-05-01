<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringKegnas extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function jadwalKegiatan()
    {
        return $this->belongsTo(JadwalKegiatan::class);
    }

    public function narasumber()
    {
        return $this->belongsTo(Narasumber::class);
    }

    public function kehadiranKegnas()
    {
        return $this->hasMany(KehadiranKegnas::class);
    }
}
