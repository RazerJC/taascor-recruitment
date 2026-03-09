<?php
require_once __DIR__ . '/lib/helpers.php';
session_start();
$flash = get_flash();
include __DIR__ . '/partials/header.php';
?>
<section class="space-y-4 text-center">
    <h1 class="text-3xl font-bold">Application Submitted</h1>
    <p class="text-slate-600 dark:text-slate-300"><?= e($flash['message'] ?? 'Thank you for applying. Our HR team will review your application.') ?></p>
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="/jobs.php" class="inline-flex items-center justify-center rounded-xl bg-primary text-white px-6 py-3 font-semibold">Browse Jobs</a>
        <a href="/" class="inline-flex items-center justify-center rounded-xl border border-slate-300 dark:border-slate-700 px-6 py-3 font-semibold">Back to Home</a>
    </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
