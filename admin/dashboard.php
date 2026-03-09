<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/db.php';
auth_start();
require_role('admin');
$stats = [
    'users' => 0,
    'applications' => 0,
    'jobs' => 0,
];
try {
    $stats['users'] = (int) db()->query('SELECT COUNT(*) as total FROM users')->fetch()['total'];
    $stats['applications'] = (int) db()->query('SELECT COUNT(*) as total FROM applications')->fetch()['total'];
    $stats['jobs'] = (int) db()->query('SELECT COUNT(*) as total FROM jobs')->fetch()['total'];
} catch (Throwable $e) {
    $stats = $stats;
}
include __DIR__ . '/../partials/header.php';
?>
<section class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Admin Dashboard</h1>
            <p class="text-slate-600 dark:text-slate-300">Full access to users, applications, and jobs.</p>
        </div>
        <div class="flex gap-3">
            <a href="/admin/users.php" class="inline-flex items-center justify-center rounded-xl bg-primary text-white px-5 py-2 text-sm font-semibold">Manage Users</a>
            <a href="/hr/dashboard.php" class="inline-flex items-center justify-center rounded-xl border border-slate-300 dark:border-slate-700 px-5 py-2 text-sm font-semibold">HR Dashboard</a>
        </div>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-800">
            <p class="text-sm text-slate-500">Total Users</p>
            <p class="text-3xl font-bold"><?= e($stats['users']) ?></p>
        </div>
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-800">
            <p class="text-sm text-slate-500">Applications</p>
            <p class="text-3xl font-bold"><?= e($stats['applications']) ?></p>
        </div>
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-800">
            <p class="text-sm text-slate-500">Job Posts</p>
            <p class="text-3xl font-bold"><?= e($stats['jobs']) ?></p>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
