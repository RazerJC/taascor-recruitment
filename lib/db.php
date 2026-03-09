<?php
function db()
{
    static $pdo = null;
    if ($pdo) {
        return $pdo;
    }
    $config = require __DIR__ . '/../config.php';

    // Support Render's DATABASE_URL (postgres://user:pass@host:port/dbname)
    $dbUrl = $config['db']['url'] ?? '';
    if ($dbUrl !== '') {
        $parsed = parse_url($dbUrl);
        $dsn = 'pgsql:host=' . $parsed['host'] . ';port=' . ($parsed['port'] ?? 5432) . ';dbname=' . ltrim($parsed['path'], '/');
        $user = $parsed['user'] ?? '';
        $pass = $parsed['pass'] ?? '';
    }
    else {
        $dsn = 'pgsql:host=' . $config['db']['host'] . ';port=' . $config['db']['port'] . ';dbname=' . $config['db']['name'];
        $user = $config['db']['user'];
        $pass = $config['db']['pass'];
    }

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}
