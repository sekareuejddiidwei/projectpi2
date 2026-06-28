<?php $__env->startSection('title', 'Dashboard - PT WAGS'); ?>
<?php $__env->startSection('header_title', 'Selamat Datang, Admin!'); ?>
<?php $__env->startSection('header_subtitle', 'Ringkasan aktivitas uji kualitas material bulan ini'); ?>

<?php $__env->startSection('content'); ?>
<section class="mb-4 mb-lg-5">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-3 g-lg-4">
    <div class="col">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body p-3 p-lg-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label text-uppercase text-muted small fw-semibold mb-1">Total Uji Bulan Ini</p>
                        <h3 class="stat-value fw-bold mb-0"><?php echo e($totalUji); ?></h3>
                    </div>
                    <div class="stat-icon-box bg-primary text-light p-2 rounded-3">
                        <i data-lucide="activity"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card success shadow-sm h-100">
            <div class="card-body p-3 p-lg-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label text-uppercase text-muted small fw-semibold mb-1">Layak Kirim</p>
                        <h3 class="stat-value success-text fw-bold mb-0"><?php echo e($layakKirim); ?></h3>
                    </div>
                    <div class="stat-icon-box bg-success text-light p-2 rounded-3">
                        <i data-lucide="check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card danger shadow-sm h-100">
            <div class="card-body p-3 p-lg-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label text-uppercase text-muted small fw-semibold mb-1">Tidak Layak</p>
                        <h3 class="stat-value danger-text fw-bold mb-0"><?php echo e($tidakLayak); ?></h3>
                    </div>
                    <div class="stat-icon-box bg-danger text-light p-2 rounded-3">
                        <i data-lucide="alert-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<section class="mb-4 mb-lg-5">
    <div class="d-flex align-items-center gap-2 mb-4">
        <i data-lucide="layers" class="text-primary"></i>
        <h3 class="section-title m-0">Mulai Klasifikasi Baru</h3>
    </div>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xxl-4 g-3 g-lg-4 justify-content-center">
        <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col">
            <div class="card material-card shadow-sm h-100">
                <div class="card-body p-3 p-lg-4 d-flex flex-column align-items-center text-center gap-2">
                    <div class="icon-box mb-3">
                        <?php
                            $icon = match($material->slug) {
                                'kaolin' => 'mountain',
                                'clay' => 'brick-wall',
                                'feldspar' => 'diamond-plus',
                                'pasir-silika' => 'loader',
                                default => 'box'
                            };
                        ?>
                        <i data-lucide="<?php echo e($icon); ?>"></i>
                    </div>
                    <div class="mb-3">
                        <h4 class="h5 fw-semibold mb-1"><?php echo e($material->name); ?></h4>
                        <p class="small text-muted mb-0">Uji Kualitas Material</p>
                    </div>
                    <a href="<?php echo e(route('samples.create', ['material_id' => $material->id])); ?>" class="btn btn-outline-primary w-100 mt-auto fw-semibold">
                        <i data-lucide="play" class="me-2"></i>
                        <span>Mulai Uji</span>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<section>
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
        <div class="d-flex align-items-center gap-2">
            <i data-lucide="history" class="text-primary"></i>
            <h3 class="section-title m-0">Uji Terbaru</h3>
        </div>
        <a href="<?php echo e(route('samples.index')); ?>" class="primary-text small fw-semibold text-decoration-none mt-1 mt-sm-0">
            <span>Lihat Semua</span>
            <i data-lucide="arrow-right" class="ms-1"></i>
        </a>
    </div>

    <div class="card shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 w-100 text-nowrap">
            <thead>
                <tr>
                    <th class="ps-4 text-nowrap">Tanggal</th>
                    <th>Material</th>
                    <th>No. Sampel</th>
                    <th>Status</th>
                    <th class="text-end pe-4 text-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $latestSamples; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sample): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="ps-4 text-nowrap"><?php echo e(\Carbon\Carbon::parse($sample->test_date)->format('d/m/Y')); ?></td>
                    <td class="font-bold"><?php echo e($sample->material->name); ?></td>
                    <td><code class="code-badge"><?php echo e($sample->sample_no); ?></code></td>
                    <td>
                        <span class="badge <?php echo e($sample->status == 'Layak Kirim' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'); ?> rounded-pill px-3 py-2">
                            <?php echo e($sample->status); ?>

                        </span>
                    </td>
                    <td class="text-end pe-4 text-nowrap">
                        <a href="<?php echo e(route('samples.show', $sample->id)); ?>" class="btn btn-sm btn-outline-primary rounded-3 fw-semibold">
                            <i data-lucide="eye" class="me-1"></i>
                            Detail
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center py-4 py-md-5 text-muted">
                        <i data-lucide="inbox" class="mb-3 opacity-25" style="width: 48px; height: 48px;"></i>
                        <p>Belum ada data uji terbaru.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
            </table>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\sekar\Downloads\wags-main (1)\wags-main\resources\views/dashboard/index.blade.php ENDPATH**/ ?>