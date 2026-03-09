<?php
require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/helpers.php';
$jobs = [];
try {
    $jobs = db()->query('SELECT id, title, location, employment_type, requirements, short_description FROM jobs WHERE is_active = 1 ORDER BY created_at DESC')->fetchAll();
} catch (Throwable $e) {
    $jobs = [];
}
include __DIR__ . '/partials/header.php';
?>
<section class="space-y-4">
    <h1 class="text-3xl font-bold">Job Listings</h1>
    <p class="text-slate-600 dark:text-slate-300">Explore open positions and apply directly online.</p>
</section>

<section class="mt-10 space-y-6">
    <?php if (count($jobs) === 0): ?>
        <div class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 p-8 text-center text-slate-500">No job postings available.</div>
    <?php else: ?>
        <?php foreach ($jobs as $job): ?>
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-800">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="space-y-2">
                        <h2 class="text-xl font-semibold"><?= e($job['title']) ?></h2>
                        <p class="text-sm text-slate-500"><?= e($job['location']) ?> · <?= e($job['employment_type']) ?></p>
                        <p class="text-sm text-slate-600 dark:text-slate-300"><?= e($job['short_description']) ?></p>
                        <p class="text-sm text-slate-600 dark:text-slate-300 whitespace-pre-line"><?= e($job['requirements']) ?></p>
                    </div>
                    <div>
                        <a href="/apply.php?job_id=<?= e($job['id']) ?>" class="inline-flex items-center justify-center rounded-xl bg-primary text-white px-5 py-2 text-sm font-semibold">Apply Now</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
