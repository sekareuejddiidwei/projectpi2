<?php

namespace Database\Seeders;

use App\Models\Parameter;
use Illuminate\Database\Seeder;

class ParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parameters = [
            ['name' => 'Fe₂O₃', 'slug' => 'fe2o3'],
            ['name' => 'CaO', 'slug' => 'cao'],
            ['name' => 'SiO₂', 'slug' => 'sio2'],
        ];

        foreach ($parameters as $parameter) {
            Parameter::updateOrCreate(
                ['slug' => $parameter['slug']],
                ['name' => $parameter['name']]
            );
        }
    }
}
