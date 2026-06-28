@extends('layouts.app')

@section('title', 'Input Data Uji - ' . $materialName)
@section('header_title', 'Halaman Input Data Uji')
@section('header_subtitle', 'Masukkan hasil uji laboratorium untuk material ' . $materialName)

@section('content')
<form action="{{ route('samples.store') }}" method="POST">
    @csrf
    <div class="row g-4 mb-4">
        <!-- Informasi Sampel -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <i data-lucide="file-text" class="text-primary"></i>
                        <h3 class="section-title mb-0">Informasi Sampel</h3>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Material</label>
                        <select name="material_id" class="form-select border-2" required>
                            <option value="">Pilih Material</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}" {{ old('material_id', $selectedMaterial?->id) == $material->id ? 'selected' : '' }}>
                                    {{ $material->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('material_id')
                            <div class="text-danger small mt-1 fw-medium">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">No. Sampel *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-2 border-end-0">
                                <i data-lucide="hash" class="text-muted"></i>
                            </span>
                            <input type="text" name="sample_no" class="form-control border-2" placeholder="Contoh: LAB-2026-001" required value="{{ old('sample_no', $defaultSampleNo) }}">
                        </div>
                        @error('sample_no') 
                            <div class="text-danger small mt-1 fw-medium">{{ $message }}</div> 
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Uji *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-2 border-end-0">
                                <i data-lucide="calendar" class="text-muted"></i>
                            </span>
                            <input type="date" name="test_date" class="form-control border-2" required value="{{ old('test_date', date('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Operator *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-2 border-end-0">
                                <i data-lucide="user" class="text-muted"></i>
                            </span>
                            <input type="text" name="operator" class="form-control border-2" placeholder="Nama petugas lab" required value="{{ old('operator', $defaultOperator) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parameter Hasil Uji -->
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <i data-lucide="beaker" class="text-primary"></i>
                        <h3 class="section-title mb-0">Parameter Hasil Uji</h3>
                    </div>
                    <p class="text-muted small mb-4 bg-light p-2 rounded border">
                        <i data-lucide="info" class="me-1"></i>
                        Isi parameter sesuai hasil uji laboratorium (Standar PT WAGS)
                    </p>

                    <div class="row g-3">
                        @foreach($parameters as $parameter)
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">{{ $parameter->name }} (%)</label>
                                <div class="input-group shadow-sm-hover">
                                    <input type="number" step="0.0001" min="0" max="100" name="{{ $parameter->slug }}" class="form-control border-2" placeholder="0.00" value="{{ old($parameter->slug) }}">
                                    <span class="input-group-text bg-white text-muted fw-bold border-2 border-start-0">%</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3 justify-content-end">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4 py-2 fw-semibold">
            <i data-lucide="x" class="me-2"></i>
            Batal
        </a>
        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">
            <span class="me-2">Proses Klasifikasi</span>
            <i data-lucide="zap"></i>
        </button>
    </div>
</form>
@endsection
