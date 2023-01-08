<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    // public function siswa()
    // {
    //     return $this->belongsTo(Siswa::class);
    // }
    // public function kelas()
    // {
    //     return $this->belongsTo(Kelas::class);
    // }
}
