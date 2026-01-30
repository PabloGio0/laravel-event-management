<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at"
    ];
    
    public function cotizacion() {
        return $this->belongsToMany(Cotizacion::class, "cotizacion_servicio", "servicio_id", "cotizacion_id")->
        withPivot("cantidad")->withTimestamps();
    }
}
