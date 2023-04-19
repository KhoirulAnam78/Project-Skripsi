<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AngkatanWaliAsrama extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'angkatan_wali_asrama';
}
