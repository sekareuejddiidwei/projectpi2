@extends('layouts.app')

@section('title', 'Pengaturan Aturan - PT WAGS')
@section('header_title', 'Basis Aturan Forward Chaining')
@section('header_subtitle', 'Kelola parameter dan ambang batas untuk klasifikasi kualitas material')

@section('content')
<div class="card mb-4 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-2">
                <i data-lucide="shield-check" class="text-primary"></i>
                <h3 class="section-title mb-0">BASIS ATURAN FORWARD CHAINING</h3>
            </div>
            <button class="btn btn-outline-primary fw-bold" onclick="resetForm()">
                <i data-lucide="plus-circle" class="me-2"></i>
                <span>Tambah Aturan</span>
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead>
                    <tr>
                        <th class="ps-4">
                            <i data-lucide="package" class="me-1"></i>
                            Material
                        </th>
                        <th>
                            <i data-lucide="beaker" class="me-1"></i>
                            Parameter
                        </th>
                        <th>
                            <i data-lucide="settings-2" class="me-1"></i>
                            Kondisi
                        </th>
                        <th>
                            <i data-lucide="percent" class="me-1"></i>
                            Nilai Batas
                        </th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rules as $rule)
                    <tr>
                        <td class="ps-4 font-bold">{{ $rule->material->name }}</td>
                        <td>{{ $rule->parameter?->name ?? '—' }}</td>
                        <td>
                            @php
                                $opLabel = match($rule->operator) {
                                    '<' => 'Kurang dari',
                                    '>' => 'Lebih dari',
                                    '<=' => 'Kurang dari sama dengan',
                                    '>=' => 'Lebih dari sama dengan',
                                };
                            @endphp
                            <span class="text-muted small fw-medium">{{ $opLabel }} ({{ $rule->operator }})</span>
                        </td>
                        <td class="font-semibold text-primary">{{ $rule->value }}%</td>
                        <td class="text-end hstack justify-content-end gap-2">
                            <button onclick="editRule({{ json_encode($rule) }})" class="btn btn-sm btn-outline-secondary fw-semibold">
                                <i data-lucide="edit-3" class="me-1"></i>
                                Edit
                            </button>
                            
                            <form action="{{ route('settings.rules.destroy', $rule->id) }}" method="POST" onsubmit="return confirm('Hapus aturan ini?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold">
                                    <i data-lucide="trash-2" class="me-1"></i>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm scroll-mt-8" id="form-card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-2">
                <i data-lucide="edit" class="text-primary"></i>
                <h3 class="section-title mb-0" id="form-title">TAMBAH / EDIT ATURAN</h3>
            </div>
        </div>
        
        <form id="rule-form" action="{{ route('settings.rules.store') }}" method="POST">
            @csrf
            <div id="method-field"></div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Material</label>
                        <select name="material_id" id="material_id" class="form-select border-2" required>
                            <option value="">Pilih Material</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}" data-formula="{{ $material->formula }}">{{ $material->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Parameter</label>
                        <select name="parameter_id" id="parameter_id" class="form-select border-2" required>
                            <option value="">Pilih Parameter</option>
                            @foreach($parameters as $parameter)
                                <option value="{{ $parameter->id }}">{{ $parameter->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kondisi</label>
                        <select name="operator" id="operator" class="form-select border-2" required>
                            <option value="<">Kurang dari (<)</option>
                            <option value=">">Lebih dari (>)</option>
                            <option value="<=">Kurang dari sama dengan (<=)</option>
                            <option value=">=">Lebih dari sama dengan (>=)</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nilai Batas (%)</label>
                        <div class="input-group">
                            <input type="number" step="0.0001" name="value" id="value" class="form-control border-2" placeholder="0.5" required>
                            <span class="input-group-text bg-white border-2 border-start-0 fw-bold">%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary fw-semibold px-4" onclick="resetForm()">
                        <i data-lucide="x" class="me-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary fw-bold px-4" id="submit-btn">
                        <i data-lucide="save" class="me-2"></i>
                        <span>Simpan Aturan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const form = document.getElementById('rule-form');
    const title = document.getElementById('form-title');
    const methodField = document.getElementById('method-field');
    const submitBtn = document.getElementById('submit-btn');
    const materialSelect = document.getElementById('material_id');

    function editRule(rule) {
        title.innerText = 'TAMBAH / EDIT ATURAN';
        form.action = `/settings/rules/${rule.id}`;
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        submitBtn.innerHTML = '<i data-lucide="refresh-cw" class="me-2"></i><span>Perbarui Aturan</span>';
        
        materialSelect.value = rule.material_id;
        
        document.getElementById('parameter_id').value = rule.parameter_id;
        document.getElementById('operator').value = rule.operator;
        document.getElementById('value').value = rule.value;
        
        document.getElementById('form-card').scrollIntoView({ behavior: 'smooth' });
        
        // Re-initialize Lucide icons for the button
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function resetForm() {
        title.innerText = 'TAMBAH / EDIT ATURAN';
        form.action = "{{ route('settings.rules.store') }}";
        methodField.innerHTML = '';
        submitBtn.innerHTML = '<i data-lucide="save" class="me-2"></i><span>Simpan Aturan</span>';
        form.reset();
        
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
</script>
@endsection
