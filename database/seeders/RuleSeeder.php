<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Parameter;
use App\Models\Rule;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kaolin = Material::where('slug', 'kaolin')->first();
        $clay = Material::where('slug', 'clay')->first();
        $feldspar = Material::where('slug', 'feldspar')->first();
        $pasirSilika = Material::where('slug', 'pasir-silika')->first();

        $parameterMap = Parameter::pluck('id', 'slug');

        $rules = [
            // Kaolin Rules
            ['material_id' => $kaolin->id, 'parameter_id' => $parameterMap['fe2o3'], 'operator' => '<', 'value' => 1.0],
            ['material_id' => $kaolin->id, 'parameter_id' => $parameterMap['cao'], 'operator' => '<', 'value' => 0.5],
            
            // Clay Rules
            ['material_id' => $clay->id, 'parameter_id' => $parameterMap['fe2o3'], 'operator' => '<', 'value' => 2.0],
            ['material_id' => $clay->id, 'parameter_id' => $parameterMap['cao'], 'operator' => '<', 'value' => 1.5],
            
            // Feldspar Rules
            ['material_id' => $feldspar->id, 'parameter_id' => $parameterMap['fe2o3'], 'operator' => '<', 'value' => 0.3],
            ['material_id' => $feldspar->id, 'parameter_id' => $parameterMap['sio2'], 'operator' => '>', 'value' => 60.0],
            
            // Pasir Silika Rules
            ['material_id' => $pasirSilika->id, 'parameter_id' => $parameterMap['sio2'], 'operator' => '>', 'value' => 95.0],
            ['material_id' => $pasirSilika->id, 'parameter_id' => $parameterMap['fe2o3'], 'operator' => '<', 'value' => 0.1],
        ];

        foreach ($rules as $rule) {
            Rule::updateOrCreate(
                [
                    'material_id' => $rule['material_id'],
                    'parameter_id' => $rule['parameter_id'],
                ],
                [
                    'operator' => $rule['operator'],
                    'value' => $rule['value'],
                ]
            );
        }
    }
}
