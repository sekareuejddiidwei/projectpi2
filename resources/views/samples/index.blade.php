@extends('layouts.app')

@section('title', 'Laporan Uji - PT WAGS')
@section('header_title', 'Laporan Hasil Uji Material')
@section('header_subtitle', 'Daftar keseluruhan data hasil klasifikasi laboratorium')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-2 mb-4">
            <i data-lucide="filter" class="text-primary"></i>
            <h5 class="fw-bold mb-0">Filter Laporan</h5>
        </div>
        <form action="{{ route('samples.index') }}" method="GET" id="filter-form">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-muted fw-semibold small mb-2">
                        <i data-lucide="package" class="me-1"></i>
                        Jenis Material
                    </label>
                    <select name="material_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Material</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" {{ request('material_id') == $material->id ? 'selected' : '' }}>{{ $material->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label text-muted fw-semibold small mb-2">
                        <i data-lucide="info" class="me-1"></i>
                        Status
                    </label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="Layak Kirim" {{ request('status') == 'Layak Kirim' ? 'selected' : '' }}>Layak Kirim</option>
                        <option value="Tidak Layak" {{ request('status') == 'Tidak Layak' ? 'selected' : '' }}>Tidak Layak</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted fw-semibold small mb-2">
                        <i data-lucide="calendar" class="me-1"></i>
                        Bulan
                    </label>
                    <input type="month" name="month" class="form-control" value="{{ request('month') }}" onchange="this.form.submit()">
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-light w-100 py-2 border fw-semibold" onclick="window.location.href='{{ route('samples.index') }}'">
                        <i data-lucide="rotate-ccw" class="me-2"></i>
                        Reset
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i data-lucide="list" class="text-primary"></i>
                <h3 class="section-title mb-0">REKAPITULASI HASIL UJI</h3>
            </div>
            <a href="{{ route('samples.export', request()->all()) }}" class="btn btn-outline-success fw-bold px-4">
                <i data-lucide="download" class="me-2"></i>
                <span>Export CSV</span>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
            <thead>
                <tr>
                    <th class="ps-4 text-nowrap">No</th>
                    <th class="text-nowrap">Tanggal</th>
                    <th>Material</th>
                    <th class="text-nowrap">No. Sampel</th>
                    <th>Operator</th>
                    @foreach($parameters as $parameter)
                        <th class="text-nowrap">{{ $parameter->name }} (%)</th>
                    @endforeach
                    <th>Status</th>
                    <th class="text-end pe-4 text-nowrap">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($samples as $index => $sample)
                <tr>
                    <td class="ps-4 text-nowrap">{{ $index + 1 }}</td>
                    <td class="text-nowrap">{{ \Carbon\Carbon::parse($sample->test_date)->format('d/m/Y') }}</td>
                    <td class="font-semibold">{{ $sample->material->name }}</td>
                    <td><code class="code-badge text-primary">{{ $sample->sample_no }}</code></td>
                    <td>{{ $sample->operator }}</td>
                    @foreach($parameters as $parameter)
                        @php
                            $detail = $sample->details->where('parameter.slug', $parameter->slug)->first();
                        @endphp
                        <td class="fw-medium text-primary text-nowrap">{{ $detail ? number_format($detail->value, 2) . '%' : '-' }}</td>
                    @endforeach
                    <td>
                        <span class="badge {{ $sample->status == 'Layak Kirim' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} rounded-pill px-3 py-2">
                            {{ $sample->status }}
                        </span>
                    </td>
                    <td class="text-end pe-4 text-nowrap">
                        <a href="{{ route('samples.show', $sample->id) }}" class="btn btn-sm btn-outline-primary rounded-3 fw-semibold">
                            <i data-lucide="external-link" class="me-1"></i>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ 7 + $parameters->count() }}" class="text-center py-5 text-muted">
                        <i data-lucide="inbox" class="mb-3 opacity-25" style="width: 48px; height: 48px;"></i>
                        <p>Belum ada data laporan untuk kriteria ini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
