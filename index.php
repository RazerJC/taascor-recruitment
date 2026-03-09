<?php
require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/helpers.php';
$jobs = [];
try {
    $jobs = db()->query('SELECT id, title, location, employment_type, short_description FROM jobs WHERE is_active = 1 ORDER BY created_at DESC LIMIT 3')->fetchAll();
} catch (Throwable $e) {
    $jobs = [];
}
include __DIR__ . '/partials/header.php';
?>
<section class="grid gap-10 lg:grid-cols-2 items-center">
    <div class="space-y-6">
        <span class="inline-flex items-center rounded-full bg-primary/10 text-primary dark:text-blue-200 px-4 py-1 text-xs font-semibold">TAASCOR Recruitment</span>
        <h1 class="text-4xl lg:text-5xl font-bold leading-tight">Build your future with TAASCOR talent solutions</h1>
        <p class="text-lg text-slate-600 dark:text-slate-300">An integrated recruitment platform that connects applicants with opportunities across TAASCOR partner companies. Apply online, track your status, and let our HR team guide you.</p>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="/jobs.php" class="inline-flex items-center justify-center rounded-xl bg-primary text-white px-6 py-3 font-semibold">Browse Jobs</a>
            <a href="/apply.php" class="inline-flex items-center justify-center rounded-xl border border-slate-300 dark:border-slate-700 px-6 py-3 font-semibold">Apply Now</a>
        </div>
    </div>
    <div class="bg-gradient-to-br from-primary to-slate-900 rounded-3xl p-8 text-white shadow-xl">
        <div class="space-y-4">
            <h2 class="text-2xl font-semibold">Why TAASCOR?</h2>
            <ul class="space-y-3 text-sm">
                <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-yellow-400"></span>DOLE-certified manpower services provider with nationwide reach</li>
                <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-yellow-400"></span>Transparent recruitment workflow and responsive HR support</li>
                <li class="flex items-start gap-3"><span class="mt-1 h-2 w-2 rounded-full bg-yellow-400"></span>Multiple industries across Laguna, Cavite, Bulacan, Metro Manila, and Cebu</li>
            </ul>
        </div>
    </div>
</section>

<section class="mt-16 grid gap-6 lg:grid-cols-3">
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-3 bg-white dark:bg-slate-800">
        <h3 class="text-lg font-semibold text-primary">Vision</h3>
        <p class="text-sm text-slate-600 dark:text-slate-300">To be a trusted manpower and business solutions partner that empowers Filipino talent and elevates client productivity.</p>
    </div>
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-3 bg-white dark:bg-slate-800">
        <h3 class="text-lg font-semibold text-primary">Mission</h3>
        <p class="text-sm text-slate-600 dark:text-slate-300">Deliver reliable staffing, training, and HR services while maintaining compliance, integrity, and care for every applicant.</p>
    </div>
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-3 bg-white dark:bg-slate-800">
        <h3 class="text-lg font-semibold text-primary">Core Values</h3>
        <p class="text-sm text-slate-600 dark:text-slate-300">Service excellence, accountability, teamwork, respect, and continuous improvement.</p>
    </div>
</section>

<section class="mt-16">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Latest Openings</h2>
        <a href="/jobs.php" class="text-sm font-semibold text-accent">View all</a>
    </div>
    <?php if (count($jobs) === 0): ?>
        <div class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 p-8 text-center text-slate-500">No job postings yet. Please check back soon.</div>
    <?php else: ?>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($jobs as $job): ?>
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-800 space-y-3">
                    <h3 class="text-lg font-semibold"><?= e($job['title']) ?></h3>
                    <p class="text-sm text-slate-500"><?= e($job['location']) ?> · <?= e($job['employment_type']) ?></p>
                    <p class="text-sm text-slate-600 dark:text-slate-300"><?= e($job['short_description']) ?></p>
                    <a href="/apply.php?job_id=<?= e($job['id']) ?>" class="inline-flex text-sm font-semibold text-primary">Apply Now</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
