<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/helpers.php';
$config = require __DIR__ . '/../config.php';
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($config['site']['name']) ?></title>
    <script>
        const storedTheme = localStorage.getItem('taascor-theme');
        if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#0f3a74',
                        accent: '#c62828'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-slate-900 dark:text-slate-100">
    <header class="sticky top-0 z-40 backdrop-blur bg-white/90 dark:bg-slate-900/90 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-3">
                    <img src="<?= e($config['site']['logo_primary']) ?>" alt="TAASCOR logo" class="h-10 w-auto">
                    <img src="<?= e($config['site']['logo_wordmark']) ?>" alt="TAASCOR wordmark" class="h-8 w-auto hidden sm:block">
                </a>
                <nav class="hidden md:flex items-center gap-6 text-sm font-semibold">
                    <a href="/" class="hover:text-accent">Home</a>
                    <a href="/jobs.php" class="hover:text-accent">Jobs</a>
                    <a href="/apply.php" class="hover:text-accent">Apply</a>
                    <?php if ($user): ?>
                        <a href="<?= $user['role'] === 'admin' ? '/admin/dashboard.php' : '/hr/dashboard.php' ?>" class="hover:text-accent">Dashboard</a>
                        <a href="/logout.php" class="hover:text-accent">Logout</a>
                    <?php else: ?>
                        <a href="/login.php" class="hover:text-accent">HR/Admin</a>
                    <?php endif; ?>
                </nav>
                <div class="flex items-center gap-3">
                    <button id="themeToggle" class="hidden sm:inline-flex items-center rounded-full border border-slate-200 dark:border-slate-700 px-3 py-1 text-xs font-semibold">
                        <span class="theme-label">Dark</span>
                    </button>
                    <button id="mobileMenuButton" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-200 dark:border-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="mobileMenu" class="hidden md:hidden pb-4">
                <nav class="flex flex-col gap-3 text-sm font-semibold">
                    <a href="/" class="hover:text-accent">Home</a>
                    <a href="/jobs.php" class="hover:text-accent">Jobs</a>
                    <a href="/apply.php" class="hover:text-accent">Apply</a>
                    <?php if ($user): ?>
                        <a href="<?= $user['role'] === 'admin' ? '/admin/dashboard.php' : '/hr/dashboard.php' ?>" class="hover:text-accent">Dashboard</a>
                        <a href="/logout.php" class="hover:text-accent">Logout</a>
                    <?php else: ?>
                        <a href="/login.php" class="hover:text-accent">HR/Admin</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>
    <main class="max-w-6xl mx-auto px-4 py-10">
