<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Narasumber extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function monitoringKegnas()
    {
        return $this->hasMany(MonitoringKegnas::class);
    }
}
