<?php
require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/helpers.php';
session_start();
$config = require __DIR__ . '/config.php';
$companies = $config['companies'];
$jobs = [];
try {
    $jobs = db()->query('SELECT id, title FROM jobs WHERE is_active = 1 ORDER BY created_at DESC')->fetchAll();
} catch (Throwable $e) {
    $jobs = [];
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $jobId = (int) ($_POST['job_id'] ?? 0);
    $companyChoice = trim($_POST['company_choice'] ?? '');
    $coverLetter = trim($_POST['cover_letter'] ?? '');
    $experience = trim($_POST['experience'] ?? '');
    $education = trim($_POST['education'] ?? '');

    if ($name === '' || $email === '' || $phone === '' || $address === '') {
        $errors[] = 'Please fill out required fields.';
    }
    if ($jobId === 0) {
        $errors[] = 'Please select a position.';
    }
    if ($companyChoice === '') {
        $errors[] = 'Please select a client company.';
    }

    $resumePath = null;
    if (!empty($_FILES['resume']['name'])) {
        $uploadDir = __DIR__ . '/uploads/resumes';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = uniqid('resume_', true) . '_' . basename($_FILES['resume']['name']);
        $targetPath = $uploadDir . '/' . $fileName;
        if (move_uploaded_file($_FILES['resume']['tmp_name'], $targetPath)) {
            $resumePath = '/uploads/resumes/' . $fileName;
        }
    }

    if (count($errors) === 0) {
        $stmt = db()->prepare('INSERT INTO applications (name, email, phone, address, job_id, company_choice, resume_path, cover_letter, experience, education, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([$name, $email, $phone, $address, $jobId, $companyChoice, $resumePath, $coverLetter, $experience, $education, 'New']);
        set_flash('Application submitted successfully.', 'success');
        redirect('/apply_success.php');
    }
}

$selectedJob = (int) ($_GET['job_id'] ?? ($_POST['job_id'] ?? 0));
include __DIR__ . '/partials/header.php';
?>
<section class="space-y-4">
    <h1 class="text-3xl font-bold">Apply for a Position</h1>
    <p class="text-slate-600 dark:text-slate-300">Complete the form below and our HR team will reach out to you.</p>
</section>

<?php if (count($errors) > 0): ?>
    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 text-red-700 p-4">
        <ul class="list-disc list-inside text-sm">
            <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form class="mt-8 grid gap-6" method="post" enctype="multipart/form-data">
    <div class="grid gap-4 md:grid-cols-2">
        <div class="space-y-2">
            <label class="text-sm font-semibold">Full Name *</label>
            <input type="text" name="name" value="<?= e($_POST['name'] ?? '') ?>" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold">Email *</label>
            <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold">Phone *</label>
            <input type="text" name="phone" value="<?= e($_POST['phone'] ?? '') ?>" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold">Address *</label>
            <input type="text" name="address" value="<?= e($_POST['address'] ?? '') ?>" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2">
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="space-y-2">
            <label class="text-sm font-semibold">Position Applying For *</label>
            <select name="job_id" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2">
                <option value="">Select a position</option>
                <?php foreach ($jobs as $job): ?>
                    <option value="<?= e($job['id']) ?>" <?= $selectedJob === (int) $job['id'] ? 'selected' : '' ?>><?= e($job['title']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold">Client Company *</label>
            <select name="company_choice" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2">
                <option value="">Select company and location</option>
                <?php foreach ($companies as $company): ?>
                    <?php foreach ($company['locations'] as $location): ?>
                        <?php $value = $company['name'] . ' — ' . $location; ?>
                        <option value="<?= e($value) ?>" <?= (($_POST['company_choice'] ?? '') === $value) ? 'selected' : '' ?>><?= e($value) ?></option>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="space-y-2">
        <label class="text-sm font-semibold">Resume/CV Upload</label>
        <input type="file" name="resume" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2">
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="space-y-2">
            <label class="text-sm font-semibold">Experience</label>
            <textarea name="experience" rows="4" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2"><?= e($_POST['experience'] ?? '') ?></textarea>
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold">Education</label>
            <textarea name="education" rows="4" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2"><?= e($_POST['education'] ?? '') ?></textarea>
        </div>
    </div>

    <div class="space-y-2">
        <label class="text-sm font-semibold">Cover Letter</label>
        <textarea name="cover_letter" rows="4" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2"><?= e($_POST['cover_letter'] ?? '') ?></textarea>
    </div>

    <div class="flex flex-col sm:flex-row gap-3">
        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-primary text-white px-6 py-3 font-semibold">Submit Application</button>
        <a href="/jobs.php" class="inline-flex items-center justify-center rounded-xl border border-slate-300 dark:border-slate-700 px-6 py-3 font-semibold">View Jobs</a>
    </div>
</form>
<?php include __DIR__ . '/partials/footer.php'; ?>
