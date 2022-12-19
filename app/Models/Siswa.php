<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rombels()
    {
        return $this->hasMany(Rombel::class);
    }

    public function kehadiranPembelajarans()
    {
        return $this->hasMany(KehadiranPembelajaran::class);
    }
}
