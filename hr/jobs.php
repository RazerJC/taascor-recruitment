<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/db.php';
auth_start();
require_any_role(['hr', 'admin']);
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $employmentType = trim($_POST['employment_type'] ?? '');
        $shortDescription = trim($_POST['short_description'] ?? '');
        $requirements = trim($_POST['requirements'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        if ($title === '' || $location === '' || $employmentType === '') {
            $errors[] = 'Title, location, and employment type are required.';
        } else {
            if ($id > 0) {
                $stmt = db()->prepare('UPDATE jobs SET title = ?, location = ?, employment_type = ?, short_description = ?, requirements = ?, is_active = ? WHERE id = ?');
                $stmt->execute([$title, $location, $employmentType, $shortDescription, $requirements, $isActive, $id]);
                set_flash('Job updated successfully.');
            } else {
                $stmt = db()->prepare('INSERT INTO jobs (title, location, employment_type, short_description, requirements, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
                $stmt->execute([$title, $location, $employmentType, $shortDescription, $requirements, $isActive]);
                set_flash('Job created successfully.');
            }
            redirect('/hr/jobs.php');
        }
    }
    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = db()->prepare('DELETE FROM jobs WHERE id = ?');
            $stmt->execute([$id]);
            set_flash('Job deleted successfully.', 'success');
            redirect('/hr/jobs.php');
        }
    }
}

$editJob = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $stmt = db()->prepare('SELECT * FROM jobs WHERE id = ?');
    $stmt->execute([$editId]);
    $editJob = $stmt->fetch();
}

$jobs = db()->query('SELECT * FROM jobs ORDER BY created_at DESC')->fetchAll();
$flash = get_flash();
include __DIR__ . '/../partials/header.php';
?>
<section class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Manage Jobs</h1>
            <p class="text-slate-600 dark:text-slate-300">Create, update, and publish job openings.</p>
        </div>
        <a href="/hr/dashboard.php" class="text-sm font-semibold text-accent">Back to Dashboard</a>
    </div>

    <?php if ($flash): ?>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-800 p-4 text-sm"><?= e($flash['message']) ?></div>
    <?php endif; ?>
    <?php if (count($errors) > 0): ?>
        <div class="rounded-2xl border border-red-200 bg-red-50 text-red-700 p-4 text-sm">
            <?= e($errors[0]) ?>
        </div>
    <?php endif; ?>

    <form method="post" class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-800 space-y-4">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="<?= e($editJob['id'] ?? '') ?>">
        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-2">
                <label class="text-sm font-semibold">Job Title *</label>
                <input type="text" name="title" value="<?= e($editJob['title'] ?? '') ?>" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold">Location *</label>
                <input type="text" name="location" value="<?= e($editJob['location'] ?? '') ?>" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold">Employment Type *</label>
                <input type="text" name="employment_type" value="<?= e($editJob['employment_type'] ?? '') ?>" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold">Short Description</label>
                <input type="text" name="short_description" value="<?= e($editJob['short_description'] ?? '') ?>" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2">
            </div>
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold">Requirements</label>
            <textarea name="requirements" rows="4" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2"><?= e($editJob['requirements'] ?? '') ?></textarea>
        </div>
        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_active" <?= ($editJob['is_active'] ?? 1) ? 'checked' : '' ?>>
            <span class="text-sm">Active posting</span>
        </div>
        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-primary text-white px-6 py-3 font-semibold">
            <?= $editJob ? 'Update Job' : 'Create Job' ?>
        </button>
    </form>

    <div class="space-y-4">
        <h2 class="text-xl font-semibold">All Jobs</h2>
        <?php foreach ($jobs as $job): ?>
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-5 bg-white dark:bg-slate-800 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="font-semibold"><?= e($job['title']) ?></h3>
                    <p class="text-sm text-slate-500"><?= e($job['location']) ?> · <?= e($job['employment_type']) ?></p>
                    <p class="text-xs text-slate-500"><?= $job['is_active'] ? 'Active' : 'Inactive' ?></p>
                </div>
                <div class="flex gap-2">
                    <a href="/hr/jobs.php?edit=<?= e($job['id']) ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm font-semibold">Edit</a>
                    <form method="post">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= e($job['id']) ?>">
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-accent text-white px-4 py-2 text-sm font-semibold">Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
