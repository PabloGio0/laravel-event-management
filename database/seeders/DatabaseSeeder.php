<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Servicio::factory(10)->create();

        Cliente::factory(10)->create()->each(function ($cliente) {
            Cotizacion::factory(2)->create(["cliente_id" => $cliente->id])
                ->each(function ($cotizacion) {
                    $servicios = Servicio::inRandomOrder()->take(rand(2, 4))->get();

                    $total = 0;

                    foreach ($servicios as $servicio) {
                        $cantidad = rand(1, 5);

                        // Relación en tabla pivote
                        $cotizacion->servicio()->attach($servicio->id, [
                            'cantidad' => $cantidad
                        ]);

                        // Calcular total
                        $total += $servicio->precio * $cantidad;
                    }

                    // Guardar total final
                    $cotizacion->update(['total' => $total]);
                });
        });



        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
