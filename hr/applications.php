<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/db.php';
auth_start();
require_any_role(['hr', 'admin']);
$statusFilter = $_GET['status'] ?? '';
$search = trim($_GET['search'] ?? '');
$params = [];
$sql = 'SELECT applications.id, applications.name, applications.email, applications.phone, applications.status, applications.created_at, jobs.title AS job_title FROM applications LEFT JOIN jobs ON jobs.id = applications.job_id WHERE 1=1';
if ($statusFilter !== '') {
    $sql .= ' AND applications.status = ?';
    $params[] = $statusFilter;
}
if ($search !== '') {
    $sql .= ' AND (applications.name LIKE ? OR applications.email LIKE ? OR jobs.title LIKE ?)';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}
$sql .= ' ORDER BY applications.created_at DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$applications = $stmt->fetchAll();
include __DIR__ . '/../partials/header.php';
?>
<section class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Applications</h1>
            <p class="text-slate-600 dark:text-slate-300">Review applicants and update their status.</p>
        </div>
        <a href="/hr/dashboard.php" class="text-sm font-semibold text-accent">Back to Dashboard</a>
    </div>

    <form method="get" class="grid gap-4 md:grid-cols-3">
        <input type="text" name="search" value="<?= e($search) ?>" placeholder="Search name, email, or position" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2">
        <select name="status" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2">
            <option value="">All Status</option>
            <?php foreach (['New', 'Under Review', 'Shortlisted', 'Rejected'] as $status): ?>
                <option value="<?= e($status) ?>" <?= $statusFilter === $status ? 'selected' : '' ?>><?= e($status) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-primary text-white px-6 py-2 font-semibold">Filter</button>
    </form>

    <div class="space-y-4">
        <?php if (count($applications) === 0): ?>
            <div class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 p-8 text-center text-slate-500">No applications found.</div>
        <?php else: ?>
            <?php foreach ($applications as $app): ?>
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-5 bg-white dark:bg-slate-800 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="font-semibold"><?= e($app['name']) ?></h3>
                        <p class="text-sm text-slate-500"><?= e($app['email']) ?> · <?= e($app['phone']) ?></p>
                        <p class="text-sm text-slate-500"><?= e($app['job_title'] ?? 'Unassigned') ?></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-700"><?= e($app['status']) ?></span>
                        <a href="/hr/application.php?id=<?= e($app['id']) ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm font-semibold">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
