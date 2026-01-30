<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cotizacion extends Model
{
    use HasFactory;
    protected $table = 'cotizaciones';
    protected $guarded = [];
    public function cliente(){
        return $this->belongsTo(Cliente::class, "cliente_id");
    }

    public function servicio() {
        return $this->belongsToMany(Servicio::class, "cotizacion_servicio", "cotizacion_id", "servicio_id")
        ->as("detalle")
        ->withPivot("cantidad")->withTimestamps();
    }
}
