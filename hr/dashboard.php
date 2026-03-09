<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/db.php';
auth_start();
require_any_role(['hr', 'admin']);
$stats = [
    'applications' => 0,
    'open_jobs' => 0,
    'shortlisted' => 0,
];
try {
    $stats['applications'] = (int) db()->query('SELECT COUNT(*) as total FROM applications')->fetch()['total'];
    $stats['open_jobs'] = (int) db()->query('SELECT COUNT(*) as total FROM jobs WHERE is_active = 1')->fetch()['total'];
    $stats['shortlisted'] = (int) db()->query("SELECT COUNT(*) as total FROM applications WHERE status = 'Shortlisted'")->fetch()['total'];
} catch (Throwable $e) {
    $stats = $stats;
}
include __DIR__ . '/../partials/header.php';
?>
<section class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">HR Dashboard</h1>
            <p class="text-slate-600 dark:text-slate-300">Manage applications and job postings.</p>
        </div>
        <div class="flex gap-3">
            <a href="/hr/jobs.php" class="inline-flex items-center justify-center rounded-xl bg-primary text-white px-5 py-2 text-sm font-semibold">Manage Jobs</a>
            <a href="/hr/applications.php" class="inline-flex items-center justify-center rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2 text-sm font-semibold">View Applications</a>
        </div>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-800">
            <p class="text-sm text-slate-500">Total Applications</p>
            <p class="text-3xl font-bold"><?= e($stats['applications']) ?></p>
        </div>
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-800">
            <p class="text-sm text-slate-500">Open Jobs</p>
            <p class="text-3xl font-bold"><?= e($stats['open_jobs']) ?></p>
        </div>
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-800">
            <p class="text-sm text-slate-500">Shortlisted</p>
            <p class="text-3xl font-bold"><?= e($stats['shortlisted']) ?></p>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
