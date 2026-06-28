<?php $__env->startSection('title', 'Detail Hasil Uji - ' . $sample->sample_no); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Section -->
<div class="row mb-4 align-items-center">
    <div class="col">
        <h3 class="section-title mb-1">DETAIL HASIL KLASIFIKASI</h3>
        <p class="text-muted small mb-0">Informasi lengkap mengenai hasil uji laboratorium dan analisis sistem pakar</p>
    </div>
    <div class="col-auto">
        <a href="<?php echo e(route('samples.index')); ?>" class="btn btn-light border shadow-sm px-4">
            <i data-lucide="arrow-left" class="me-2"></i>
            <span>Kembali ke Laporan</span>
        </a>
    </div>
</div>

<!-- Status Overview -->
<div class="card shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center g-4">
            <div class="col-md-7 border-end">
                <div class="d-flex align-items-center gap-4 mb-3">
                    <div>
                        <h4 class="fw-bold mb-1"><?php echo e($sample->material->name); ?></h4>
                        <code class="text-primary fw-medium"><?php echo e($sample->sample_no); ?></code>
                    </div>
                </div>
                <div class="alert <?php echo e($sample->status == 'Layak Kirim' ? 'alert-success' : 'alert-danger'); ?> border-0 mb-0 py-3 px-4">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i data-lucide="info"></i>
                        <span class="fw-bold text-uppercase small">Kesimpulan Klasifikasi</span>
                    </div>
                    <p class="mb-0 small opacity-75">
                        <?php echo e($sample->status == 'Layak Kirim' 
                            ? 'Berdasarkan parameter kimia yang diuji, material ini dinyatakan LAYAK KIRIM karena memenuhi semua standar ambang batas yang ditentukan.' 
                            : 'Material ini dinyatakan TIDAK LAYAK karena ditemukan satu atau lebih parameter kimia yang berada di luar rentang toleransi standar.'); ?>

                    </p>
                </div>
            </div>
            <div class="col-md-5">
                <div class="ps-md-4">
                    <div class="mb-3">
                        <label class="text-muted small fw-semibold d-block mb-1">STATUS AKHIR</label>
                        <span class="badge <?php echo e($sample->status == 'Layak Kirim' ? 'bg-success' : 'bg-danger'); ?> rounded-pill px-4 py-2 fs-6">
                            <?php echo e(strtoupper($sample->status)); ?>

                        </span>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="text-muted small fw-semibold d-block mb-1">TANGGAL UJI</label>
                            <p class="mb-0 fw-bold"><?php echo e(\Carbon\Carbon::parse($sample->test_date)->format('d M Y')); ?></p>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small fw-semibold d-block mb-1">OPERATOR</label>
                            <p class="mb-0 fw-bold"><?php echo e($sample->operator); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Parameter Details -->
    <div class="col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-body p-4">
                <h3 class="section-title mb-4">HASIL PARAMETER LABORATORIUM</h3>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th class="ps-4">Parameter Kimia</th>
                                <th>Hasil Uji</th>
                                <th>Ambang Batas</th>
                                <th class="text-end pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $rulesByParameter = $sample->material->rules->keyBy('parameter_id');
                            ?>
                            <?php $__currentLoopData = $sample->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $rule = $rulesByParameter->get($detail->parameter_id);
                                    $standard = '—';
                                    $isPassed = true;
                                    
                                    if ($rule) {
                                        $standard = $rule->operator . ' ' . $rule->value . '%';
                                        $isPassed = match($rule->operator) {
                                            '<' => $detail->value < $rule->value,
                                            '>' => $detail->value > $rule->value,
                                            '<=' => $detail->value <= $rule->value,
                                            '>=' => $detail->value >= $rule->value,
                                            default => true
                                        };
                                    }
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold d-block"><?php echo e($detail->parameter->name); ?></span>
                                        <span class="text-muted extra-small">Parameter Kimia Teruji</span>
                                    </td>
                                    <td>
                                        <span class="text-primary fw-bold fs-5"><?php echo e(number_format($detail->value, 2)); ?>%</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?php echo e($standard); ?></span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <?php if($rule): ?>
                                            <span class="badge <?php echo e($isPassed ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'); ?> rounded-pill px-3 py-2">
                                                <?php echo e($isPassed ? 'Memenuhi' : 'Tidak Memenuhi'); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border rounded-pill px-3 py-2">Informasi</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- System Chaining Log -->
    <div class="col-lg-4">
        <div class="card shadow-sm h-100 bg-light-subtle">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <i data-lucide="cpu" class="text-primary"></i>
                    <h3 class="section-title mb-0">FORWARD CHAINING LOG</h3>
                </div>
                
                <div class="expert-system-log">
                    <div class="mb-3">
                        <span class="badge bg-primary px-3 py-2">RULE ENGINE</span>
                    </div>
                    
                    <div class="code-block p-4 bg-dark text-light rounded-3 shadow-inner shadow-sm font-monospace small" style="background: #1a1a1a !important;">
                        <p class="mb-2 text-secondary opacity-50">// Analisis Inferensi</p>
                        <p class="mb-2"><span class="text-warning">IF</span> material == <span class="text-info">"<?php echo e($sample->material->name); ?>"</span></p>
                        
                        <?php $__currentLoopData = $sample->material->rules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p class="mb-2 ms-4">
                                <span class="text-warning">AND</span> <?php echo e($rule->parameter?->name); ?> 
                                <span class="text-danger"><?php echo e($rule->operator); ?></span> 
                                <span class="text-success"><?php echo e($rule->value); ?>%</span>
                            </p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <div class="mt-4 pt-3 border-top border-secondary">
                            <p class="mb-0"><span class="text-warning">THEN</span> status = </p>
                            <p class="ms-4 mt-2 fs-6 fw-bold text-<?php echo e($sample->status == 'Layak Kirim' ? 'success' : 'danger'); ?>">
                                "<?php echo e(strtoupper($sample->status)); ?>"
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-white border rounded-3 small">
                    <div class="d-flex align-items-start gap-3">
                        <i data-lucide="shield-check" class="text-success flex-shrink-0"></i>
                        <div>
                            <p class="fw-bold mb-1">Verifikasi Sistem</p>
                            <p class="text-muted mb-0">Logika klasifikasi dijalankan secara otomatis berdasarkan basis pengetahuan PT WAGS.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\sekar\Downloads\wags-main (1)\wags-main\resources\views/samples/show.blade.php ENDPATH**/ ?>