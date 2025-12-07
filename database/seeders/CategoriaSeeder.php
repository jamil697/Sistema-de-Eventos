<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nombre' => 'Social',      'slug' => 'social'],
            ['nombre' => 'Cultural',    'slug' => 'cultural'],
            ['nombre' => 'Deportivo',   'slug' => 'deportivo'],
            ['nombre' => 'Corporativo', 'slug' => 'corporativo'],
            ['nombre' => 'Otros',       'slug' => 'otros'],
        ];

        foreach ($data as $cat) {
            // firstOrCreate es perfecto: evita duplicados si ejecutas el seeder dos veces
            Categoria::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
        
        // ¡HE BORRADO LA LÍNEA $this->call(...) QUE CAUSABA EL ERROR!
    }
}
