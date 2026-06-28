<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Parameter;
use App\Models\Sample;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = Material::all();
        $parameterMap = Parameter::pluck('id', 'slug')->all();
        $operators = ['Budi Santoso', 'Siti Aminah', 'Agus Wijaya', 'Rina Kurnia', 'Dedi Pratama'];

        $sampleCount = 0;

        foreach ($materials as $material) {
            // Generate 8 samples per material
            for ($i = 1; $i <= 8; $i++) {
                $sampleCount++;
                $sampleNo = 'LAB-2026-' . str_pad($sampleCount, 3, '0', STR_PAD_LEFT);
                
                // Spread dates across the last 3 months
                $testDate = Carbon::now()->subMonths(rand(0, 3))->subDays(rand(1, 28))->subHours(rand(1, 23));
                
                // Randomly decide if it's "Layak" or "Tidak Layak" (75% chance Layak)
                $isLayak = rand(0, 100) > 25; 
                $status = $isLayak ? 'Layak Kirim' : 'Tidak Layak';
                
                $sample = Sample::create([
                    'material_id' => $material->id,
                    'sample_no' => $sampleNo,
                    'test_date' => $testDate,
                    'operator' => $operators[array_rand($operators)],
                    'status' => $status,
                ]);

                // Add details based on material type and desired status
                // Using 0-100 scale for values (mutator handles DB conversion)
                switch ($material->slug) {
                    case 'kaolin':
                        // Rule: Fe2O3 < 1.0, CaO < 0.5
                        if ($isLayak) {
                            $fe = rand(10, 95) / 100; // 0.1 - 0.95
                            $ca = rand(5, 45) / 100;  // 0.05 - 0.45
                        } else {
                            $fe = rand(110, 250) / 100; // Fails (> 1.0)
                            $ca = rand(55, 120) / 100;  // Fails (> 0.5)
                        }
                        $sample->details()->create(['parameter_id' => $parameterMap['fe2o3'], 'value' => $fe]);
                        $sample->details()->create(['parameter_id' => $parameterMap['cao'], 'value' => $ca]);
                        $sample->details()->create(['parameter_id' => $parameterMap['sio2'], 'value' => rand(4500, 5500) / 100]);
                        break;

                    case 'clay':
                        // Rule: Fe2O3 < 2.0, CaO < 1.5
                        if ($isLayak) {
                            $fe = rand(50, 190) / 100; // 0.5 - 1.9
                            $ca = rand(20, 140) / 100; // 0.2 - 1.4
                        } else {
                            $fe = rand(210, 450) / 100; // Fails (> 2.0)
                            $ca = rand(160, 300) / 100; // Fails (> 1.5)
                        }
                        $sample->details()->create(['parameter_id' => $parameterMap['fe2o3'], 'value' => $fe]);
                        $sample->details()->create(['parameter_id' => $parameterMap['cao'], 'value' => $ca]);
                        $sample->details()->create(['parameter_id' => $parameterMap['sio2'], 'value' => rand(5000, 6500) / 100]);
                        break;

                    case 'feldspar':
                        // Rule: Fe2O3 < 0.3, SiO2 > 60.0
                        if ($isLayak) {
                            $fe = rand(5, 28) / 100;     // 0.05 - 0.28
                            $si = rand(6100, 7500) / 100; // 61 - 75
                        } else {
                            $fe = rand(35, 120) / 100;    // Fails (> 0.3)
                            $si = rand(4500, 5900) / 100; // Fails (< 60)
                        }
                        $sample->details()->create(['parameter_id' => $parameterMap['fe2o3'], 'value' => $fe]);
                        $sample->details()->create(['parameter_id' => $parameterMap['sio2'], 'value' => $si]);
                        $sample->details()->create(['parameter_id' => $parameterMap['cao'], 'value' => rand(100, 500) / 100]);
                        break;

                    case 'pasir-silika':
                        // Rule: SiO2 > 95.0, Fe2O3 < 0.1
                        if ($isLayak) {
                            $si = rand(9550, 9950) / 100; // 95.5 - 99.5
                            $fe = rand(1, 9) / 100;       // 0.01 - 0.09
                        } else {
                            $si = rand(8500, 9450) / 100; // Fails (< 95.0)
                            $fe = rand(15, 80) / 100;     // Fails (> 0.1)
                        }
                        $sample->details()->create(['parameter_id' => $parameterMap['sio2'], 'value' => $si]);
                        $sample->details()->create(['parameter_id' => $parameterMap['fe2o3'], 'value' => $fe]);
                        $sample->details()->create(['parameter_id' => $parameterMap['cao'], 'value' => rand(10, 50) / 100]);
                        break;
                }
            }
        }
    }
}
