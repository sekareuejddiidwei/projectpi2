<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            ['name' => 'Kaolin', 'slug' => 'kaolin', 'formula' => 'Al2Si2O5(OH)4'],
            ['name' => 'Clay', 'slug' => 'clay', 'formula' => 'Al2O3.2SiO2.2H2O'],
            ['name' => 'Feldspar', 'slug' => 'feldspar', 'formula' => 'KAlSi3O8'],
            ['name' => 'Pasir Silika', 'slug' => 'pasir-silika', 'formula' => 'SiO2'],
        ];

        foreach ($materials as $data) {
            Material::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name' => $data['name'],
                    'formula' => $data['formula'],
                ]
            );
        }
    }
}
