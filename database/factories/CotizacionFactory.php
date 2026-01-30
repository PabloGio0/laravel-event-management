<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Cotizacion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cotizacion>
 */
class CotizacionFactory extends Factory
{
    use HasFactory;
    protected $model = Cotizacion::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cliente_id' => Cliente::factory(),
            'evento' => $this->faker->randomElement(['Boda', 'XV años', 'Evento empresarial', 'Concierto']),
            'fecha_evento' => $this->faker->dateTimeBetween('now', '+3 months'),
            'total' => 0,
        ];
    }
}
