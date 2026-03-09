<?php
function db()
{
    static $pdo = null;
    if ($pdo) {
        return $pdo;
    }
    $config = require __DIR__ . '/../config.php';
    $dsn = 'mysql:host=' . $config['db']['host'] . ';port=' . $config['db']['port'] . ';dbname=' . $config['db']['name'] . ';charset=' . $config['db']['charset'];
    $pdo = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}
