<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/lib/helpers.php';
auth_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($email === '' || $password === '') {
        $errors[] = 'Email and password are required.';
    } elseif (!login($email, $password)) {
        $errors[] = 'Invalid credentials.';
    } else {
        $user = current_user();
        if ($user['role'] === 'admin') {
            redirect('/admin/dashboard.php');
        }
        redirect('/hr/dashboard.php');
    }
}
include __DIR__ . '/partials/header.php';
?>
<section class="max-w-lg mx-auto space-y-4">
    <h1 class="text-3xl font-bold text-center">HR / Admin Login</h1>
    <p class="text-center text-slate-600 dark:text-slate-300">Access the recruitment dashboard.</p>
    <?php if (count($errors) > 0): ?>
        <div class="rounded-2xl border border-red-200 bg-red-50 text-red-700 p-4">
            <ul class="list-disc list-inside text-sm">
                <?php foreach ($errors as $error): ?>
                    <li><?= e($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form method="post" class="rounded-2xl border border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-slate-800 space-y-4">
        <div class="space-y-2">
            <label class="text-sm font-semibold">Email</label>
            <input type="email" name="email" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold">Password</label>
            <input type="password" name="password" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2">
        </div>
        <button type="submit" class="w-full rounded-xl bg-primary text-white py-3 font-semibold">Login</button>
    </form>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
