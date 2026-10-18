<?php
    $featuredJobs = \App\Models\Job::publiclyVisible()->latest()->take(3)->get();
?>

<section class="featured-jobs">
    <div class="container-public">

        <div class="featured-jobs__header">
            <div class="featured-jobs__header-text">
                <span class="section-tag">Karir</span>
                <h2 class="section-title">Lowongan Terbaru</h2>
                <p class="section-desc">Bergabunglah dengan tim kami dan tumbuh bersama CV Maju Berkah</p>
            </div>
            <a href="<?php echo e(route('careers.index')); ?>" class="section-link">
                <span>Lihat Semua</span>
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>

        <?php if($featuredJobs->isEmpty()): ?>
            <div class="empty-state">
                <span class="material-symbols-outlined">work_off</span>
                <p>Belum ada lowongan aktif saat ini.</p>
            </div>
        <?php else: ?>
            <div class="featured-jobs__grid">

                <?php $__currentLoopData = $featuredJobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('careers.show', $job)); ?>" class="job-card">

                        <div class="job-card__badges">
                            <span class="job-card__badge">
                                <span class="job-card__badge-dot"></span>
                                <?php echo e($job->employment_type_label); ?>

                            </span>
                        </div>

                        <h3 class="job-card__title"><?php echo e($job->title); ?></h3>
                        <span class="job-card__category"><?php echo e($job->category); ?></span>

                        <div class="job-card__meta">
                            <span class="job-card__meta-item">
                                <span class="material-symbols-outlined">location_on</span>
                                <?php echo e($job->location); ?>

                            </span>
                            <span class="job-card__meta-item job-card__meta-item--deadline">
                                <span class="material-symbols-outlined">hourglass_top</span>
                                Batas: <?php echo e($job->deadline->format('d M Y')); ?>

                            </span>
                        </div>

                        <p class="job-card__desc">
                            <?php echo e(\Illuminate\Support\Str::limit($job->description, 120)); ?>

                        </p>

                        <div class="job-card__footer">
                            <span class="job-card__action">
                                Lihat Detail
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </span>
                        </div>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        <?php endif; ?>

    </div>
</section><?php /**PATH C:\laragon\www\cvmb-pembagian-kerja\1-auth-dan-integrasi\resources\views/home/featured-jobs.blade.php ENDPATH**/ ?>