<?php

namespace Database\Factories;

use App\Models\Servicio;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Servicio>
 */
class ServicioFactory extends Factory
{
    use HasFactory;

    protected $model = Servicio::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->unique()->randomElement([
                'Audio profesional',
                'Iluminación Beam',
                'Pantallas LED',
                'Micrófonos',
                'DJ',
                'ParLeds',
                'Sillas',
                'Mesas',
                'Manteles',
                'Batucada',
                'Meseros',
                'Sala Lounge'
            ]),
            'precio' => $this->faker->randomFloat(2, 500, 8000),
        ];
    }
}
