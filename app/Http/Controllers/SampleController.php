<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Parameter;
use App\Models\Rule;
use App\Models\Sample;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    /**
     * List all test reports.
     */
    public function index(Request $request)
    {
        $query = Sample::with(['material', 'details.parameter']);

        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            $query->whereMonth('test_date', Carbon::parse($request->month)->month)
                ->whereYear('test_date', Carbon::parse($request->month)->year);
        }

        $samples = $query->latest()->get();
        $materials = Material::all();
        $parameters = Parameter::all();

        return view('samples.index', compact('samples', 'materials', 'parameters'));
    }

    /**
     * Show form for new test.
     */
    public function create(Request $request)
    {
        $materials = Material::orderBy('name')->get();
        $selectedMaterial = $materials->firstWhere('id', (int) $request->input('material_id'));
        $parameters = Parameter::all();
        $defaultSampleNo = $this->generateSampleNo();
        $defaultOperator = auth()->user()?->name;
        $materialName = $selectedMaterial?->name ?? 'Material';

        return view('samples.create', compact(
            'materials',
            'selectedMaterial',
            'parameters',
            'defaultSampleNo',
            'defaultOperator',
            'materialName'
        ));
    }

    /**
     * Build the next sample number for the current year.
     */
    private function generateSampleNo(): string
    {
        $year = now()->format('Y');
        $prefix = "LAB-{$year}-";

        $lastSample = Sample::where('sample_no', 'like', $prefix.'%')
            ->orderBy('sample_no', 'desc')
            ->first();

        $lastNumber = 0;
        if ($lastSample) {
            $lastPart = str_replace($prefix, '', $lastSample->sample_no);
            $lastNumber = ctype_digit($lastPart) ? (int) $lastPart : 0;
        }

        $nextNumber = str_pad((string) ($lastNumber + 1), 3, '0', STR_PAD_LEFT);

        return $prefix.$nextNumber;
    }

    /**
     * Process test data and run Forward Chaining.
     */
    public function store(Request $request)
    {
        $parameterSlugs = Parameter::pluck('slug')->all();
        $validation = [
            'material_id' => 'required|exists:materials,id',
            'sample_no' => 'required|unique:samples,sample_no',
            'test_date' => 'required|date',
            'operator' => 'required|string',
        ];

        foreach ($parameterSlugs as $slug) {
            $validation[$slug] = 'nullable|numeric|min:0|max:100';
        }

        $request->validate($validation);

        $material = Material::findOrFail($request->material_id);
        $rules = Rule::with('parameter')->where('material_id', $material->id)->get();

        // Prepare parameter values (now handled by model mutators)
        $rawParams = $parameterSlugs;
        $processedParams = [];
        foreach ($rawParams as $p) {
            if ($request->has($p) && $request->input($p) !== null) {
                $processedParams[$p] = $request->input($p);
            }
        }

        // Forward Chaining Logic
        $status = 'Layak Kirim';
        foreach ($rules as $rule) {
            $paramKey = $rule->parameter?->slug;
            $paramValue = $paramKey ? ($processedParams[$paramKey] ?? null) : null;

            if ($paramValue === null) {
                continue;
            }

            $passed = false;
            switch ($rule->operator) {
                case '<':  $passed = $paramValue < $rule->value;
                    break;
                case '>':  $passed = $paramValue > $rule->value;
                    break;
                case '<=': $passed = $paramValue <= $rule->value;
                    break;
                case '>=': $passed = $paramValue >= $rule->value;
                    break;
            }

            if (! $passed) {
                $status = 'Tidak Layak';
                break;
            }
        }

        // Save Sample Metadata
        $sample = Sample::create([
            'material_id' => $material->id,
            'sample_no' => $request->sample_no,
            'test_date' => $request->test_date,
            'operator' => $request->operator,
            'status' => $status,
        ]);

        // Save Sample Details
        $parameterMap = Parameter::whereIn('slug', array_keys($processedParams))
            ->get()
            ->keyBy('slug');

        foreach ($processedParams as $param => $value) {
            $parameter = $parameterMap->get($param);

            if ($parameter) {
                $sample->details()->create([
                    'parameter_id' => $parameter->id,
                    'value' => $value,
                ]);
            }
        }

        return redirect()->route('samples.show', $sample)
            ->with('status', 'Klasifikasi berhasil dilakukan.');
    }

    /**
     * Show sample details and classification result.
     */
    public function show(Sample $sample)
    {
        $sample->load(['material.rules.parameter', 'details.parameter']);

        return view('samples.show', compact('sample'));
    }

    /**
     * Export samples to CSV.
     */
    public function exportCsv(Request $request)
    {
        $query = Sample::with(['material', 'details.parameter']);

        if ($request->filled('material_id')) {
            $query->where('material_id', $request->material_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('month')) {
            $query->whereMonth('test_date', Carbon::parse($request->month)->month)
                ->whereYear('test_date', Carbon::parse($request->month)->year);
        }

        $samples = $query->latest()->get();

        $filename = 'Laporan_Uji_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $parameters = Parameter::all();
        $columns = array_merge(
            ['No', 'Tanggal', 'Material', 'No. Sampel', 'Operator'],
            $parameters->pluck('name')->all(),
            ['Status']
        );

        $callback = function () use ($samples, $columns, $parameters) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($samples as $index => $sample) {
                $row = [
                    $index + 1,
                    $sample->test_date,
                    $sample->material->name,
                    $sample->sample_no,
                    $sample->operator,
                ];

                foreach ($parameters as $parameter) {
                    $detail = $sample->details->where('parameter.slug', $parameter->slug)->first();
                    $row[] = $detail ? $detail->value.'%' : '-';
                }

                $row[] = $sample->status;

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
