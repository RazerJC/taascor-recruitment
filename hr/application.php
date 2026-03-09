<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/db.php';
auth_start();
require_any_role(['hr', 'admin']);
$id = (int) ($_GET['id'] ?? 0);
if ($id === 0) {
    redirect('/hr/applications.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? 'New';
    $stmt = db()->prepare('UPDATE applications SET status = ? WHERE id = ?');
    $stmt->execute([$status, $id]);
    set_flash('Application status updated.');
    redirect('/hr/application.php?id=' . $id);
}
$stmt = db()->prepare('SELECT applications.*, jobs.title AS job_title FROM applications LEFT JOIN jobs ON jobs.id = applications.job_id WHERE applications.id = ?');
$stmt->execute([$id]);
$app = $stmt->fetch();
if (!$app) {
    redirect('/hr/applications.php');
}
$flash = get_flash();
include __DIR__ . '/../partials/header.php';
?>
<section class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold"><?= e($app['name']) ?></h1>
            <p class="text-slate-600 dark:text-slate-300"><?= e($app['job_title'] ?? 'Unassigned') ?></p>
        </div>
        <a href="/hr/applications.php" class="text-sm font-semibold text-accent">Back to Applications</a>
    </div>

    <?php if ($flash): ?>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-800 p-4 text-sm"><?= e($flash['message']) ?></div>
    <?php endif; ?>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-800 space-y-2">
            <p class="text-sm text-slate-500">Email</p>
            <p class="font-semibold"><?= e($app['email']) ?></p>
            <p class="text-sm text-slate-500">Phone</p>
            <p class="font-semibold"><?= e($app['phone']) ?></p>
            <p class="text-sm text-slate-500">Address</p>
            <p class="font-semibold"><?= e($app['address']) ?></p>
        </div>
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-800 space-y-2">
            <p class="text-sm text-slate-500">Company Choice</p>
            <p class="font-semibold"><?= e($app['company_choice']) ?></p>
            <p class="text-sm text-slate-500">Resume</p>
            <?php if ($app['resume_path']): ?>
                <a href="<?= e($app['resume_path']) ?>" class="text-primary font-semibold text-sm">Download Resume</a>
            <?php else: ?>
                <p class="text-sm text-slate-500">No resume uploaded</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-800 space-y-4">
        <div>
            <p class="text-sm text-slate-500">Experience</p>
            <p class="text-sm text-slate-700 dark:text-slate-200 whitespace-pre-line"><?= e($app['experience']) ?></p>
        </div>
        <div>
            <p class="text-sm text-slate-500">Education</p>
            <p class="text-sm text-slate-700 dark:text-slate-200 whitespace-pre-line"><?= e($app['education']) ?></p>
        </div>
        <div>
            <p class="text-sm text-slate-500">Cover Letter</p>
            <p class="text-sm text-slate-700 dark:text-slate-200 whitespace-pre-line"><?= e($app['cover_letter']) ?></p>
        </div>
    </div>

    <form method="post" class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-800 flex flex-col sm:flex-row sm:items-center gap-4">
        <select name="status" class="rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2">
            <?php foreach (['New', 'Under Review', 'Shortlisted', 'Rejected'] as $status): ?>
                <option value="<?= e($status) ?>" <?= $app['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-primary text-white px-6 py-2 font-semibold">Update Status</button>
    </form>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
