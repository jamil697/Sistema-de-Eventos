<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evento>
 */
class EventoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake('es_ES');
        // Generar una fecha de inicio aleatoria en los próximos 3 meses
        $fechaInicio = fake()->dateTimeBetween('now', '+3 months');
        
        // Clonar la fecha de inicio y sumarle horas para la fecha fin
        $fechaFin = (clone $fechaInicio)->modify('+' . rand(2, 48) . ' hours');

        return [
            // 1. TÍTULO: Combinamos palabras para que parezca un evento real
            // Ej: "Seminario de Tecnología 2025"
            'titulo' => fake()->randomElement(['Conferencia', 'Taller', 'Seminario', 'Torneo', 'Festival', 'Curso']) . ' ' . 
                        fake()->randomElement(['de Tecnología', 'de Arte', 'Gastronómico', 'Deportivo', 'de Marketing', 'de Salud']) . ' ' . 
                        date('Y'),

            // 2. DESCRIPCIÓN: realText() saca texto de libros en español
            'descripcion' => fake()->realText(400), 

            // 3. LUGAR: address() saldrá en español (Calle, Avenida...) si limpiaste caché
            'lugar' => fake()->address(),

            'fecha_inicio' => $fechaInicio,
            'fecha_fin'    => $fechaFin,
            'cupo'         => fake()->numberBetween(10, 500),
            
            'categoria_id' => Categoria::inRandomOrder()->first()?->id,
            'created_by'   => User::first()?->id ?? 1,
            'imagen'       => null, 
        ];
    }
}
