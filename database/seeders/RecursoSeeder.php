<?php

namespace Database\Seeders;

use App\Models\Recurso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recursos = [
            // MOBILIARIO
            [
                'nombre' => 'Sillas de plástico blancas',
                'descripcion' => 'Sillas apilables para eventos al aire libre o auditorios.',
                'cantidad' => 200
            ],
            [
                'nombre' => 'Mesas plegables (Tablones)',
                'descripcion' => 'Mesas rectangulares de 2 metros.',
                'cantidad' => 50
            ],
            [
                'nombre' => 'Toldo 3x3',
                'descripcion' => 'Carpa blanca impermeable para stands o sombra.',
                'cantidad' => 10
            ],
            [
                'nombre' => 'Estrado / Tarima pequeña',
                'descripcion' => 'Estructura modular para presentaciones.',
                'cantidad' => 2
            ],

            // TECNOLOGÍA / AUDIOVISUAL
            [
                'nombre' => 'Proyector Multimedia Epson',
                'descripcion' => 'Proyector HDMI de alta luminosidad.',
                'cantidad' => 3
            ],
            [
                'nombre' => 'Equipo de Sonido Portátil',
                'descripcion' => 'Parlante activo con micrófono inalámbrico.',
                'cantidad' => 4
            ],
            [
                'nombre' => 'Laptop Institucional',
                'descripcion' => 'Laptop Lenovo para presentaciones y control.',
                'cantidad' => 5
            ],
            [
                'nombre' => 'Micrófono Alámbrico',
                'descripcion' => 'Micrófono de mano con cable canon.',
                'cantidad' => 10
            ],

            // VEHÍCULOS / LOGÍSTICA
            [
                'nombre' => 'Camioneta Municipal (Placa EGC-123)',
                'descripcion' => 'Camioneta doble cabina para traslado de personal.',
                'cantidad' => 1
            ],
            [
                'nombre' => 'Furgoneta de Carga',
                'descripcion' => 'Vehículo para transporte de mobiliario y equipos.',
                'cantidad' => 1
            ],

            // OTROS
            [
                'nombre' => 'Podium de madera',
                'descripcion' => 'Atril para conferencias con escudo municipal.',
                'cantidad' => 2
            ],
            [
                'nombre' => 'Kit de Primeros Auxilios',
                'descripcion' => 'Mochila de emergencia para eventos masivos.',
                'cantidad' => 5
            ],
        ];

        foreach ($recursos as $item) {
            // Usamos firstOrCreate para no duplicarlos si corres el seeder dos veces
            Recurso::firstOrCreate(
                ['nombre' => $item['nombre']], // Busca por nombre
                [
                    'descripcion' => $item['descripcion'],
                    'cantidad' => $item['cantidad']
                ]
            );
        }
    }
}
