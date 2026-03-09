<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/db.php';
auth_start();
require_role('admin');
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = trim($_POST['role'] ?? 'hr');
        $password = trim($_POST['password'] ?? '');
        if ($name === '' || $email === '') {
            $errors[] = 'Name and email are required.';
        } else {
            if ($id > 0) {
                if ($password !== '') {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = db()->prepare('UPDATE users SET name = ?, email = ?, role = ?, password_hash = ? WHERE id = ?');
                    $stmt->execute([$name, $email, $role, $hash, $id]);
                } else {
                    $stmt = db()->prepare('UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?');
                    $stmt->execute([$name, $email, $role, $id]);
                }
                set_flash('User updated successfully.');
            } else {
                if ($password === '') {
                    $errors[] = 'Password is required for new users.';
                } else {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = db()->prepare('INSERT INTO users (name, email, role, password_hash, created_at) VALUES (?, ?, ?, ?, NOW())');
                    $stmt->execute([$name, $email, $role, $hash]);
                    set_flash('User created successfully.');
                }
            }
            if (count($errors) === 0) {
                redirect('/admin/users.php');
            }
        }
    }
    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = db()->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$id]);
            set_flash('User deleted successfully.');
            redirect('/admin/users.php');
        }
    }
}

$editUser = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$editId]);
    $editUser = $stmt->fetch();
}

$users = db()->query('SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
$flash = get_flash();
include __DIR__ . '/../partials/header.php';
?>
<section class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">User Management</h1>
            <p class="text-slate-600 dark:text-slate-300">Create and manage HR and Admin accounts.</p>
        </div>
        <a href="/admin/dashboard.php" class="text-sm font-semibold text-accent">Back to Dashboard</a>
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
        <input type="hidden" name="id" value="<?= e($editUser['id'] ?? '') ?>">
        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-2">
                <label class="text-sm font-semibold">Name *</label>
                <input type="text" name="name" value="<?= e($editUser['name'] ?? '') ?>" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold">Email *</label>
                <input type="email" name="email" value="<?= e($editUser['email'] ?? '') ?>" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold">Role</label>
                <select name="role" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2">
                    <option value="hr" <?= ($editUser['role'] ?? 'hr') === 'hr' ? 'selected' : '' ?>>HR</option>
                    <option value="admin" <?= ($editUser['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold"><?= $editUser ? 'New Password' : 'Password *' ?></label>
                <input type="password" name="password" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2">
            </div>
        </div>
        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-primary text-white px-6 py-3 font-semibold">
            <?= $editUser ? 'Update User' : 'Create User' ?>
        </button>
    </form>

    <div class="space-y-4">
        <h2 class="text-xl font-semibold">All Users</h2>
        <?php foreach ($users as $user): ?>
            <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-5 bg-white dark:bg-slate-800 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="font-semibold"><?= e($user['name']) ?></h3>
                    <p class="text-sm text-slate-500"><?= e($user['email']) ?> · <?= e(strtoupper($user['role'])) ?></p>
                </div>
                <div class="flex gap-2">
                    <a href="/admin/users.php?edit=<?= e($user['id']) ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-300 dark:border-slate-700 px-4 py-2 text-sm font-semibold">Edit</a>
                    <form method="post">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= e($user['id']) ?>">
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-accent text-white px-4 py-2 text-sm font-semibold">Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
