<?php
require_once __DIR__ . '/lib/db.php';

$config = require __DIR__ . '/config.php';
$rootDsn = 'mysql:host=' . $config['db']['host'] . ';port=' . $config['db']['port'] . ';charset=' . $config['db']['charset'];
$rootPdo = new PDO($rootDsn, $config['db']['user'], $config['db']['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
$rootPdo->exec('CREATE DATABASE IF NOT EXISTS `' . $config['db']['name'] . '` CHARACTER SET ' . $config['db']['charset'] . ' COLLATE ' . $config['db']['charset'] . '_general_ci');

$pdo = db();
$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        role ENUM('hr','admin') NOT NULL DEFAULT 'hr',
        password_hash VARCHAR(255) NOT NULL,
        created_at DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS jobs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        location VARCHAR(200) NOT NULL,
        employment_type VARCHAR(80) NOT NULL,
        short_description VARCHAR(255) DEFAULT '',
        requirements TEXT,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(200) NOT NULL,
        email VARCHAR(150) NOT NULL,
        phone VARCHAR(80) NOT NULL,
        address VARCHAR(255) NOT NULL,
        job_id INT NULL,
        company_choice VARCHAR(255) NOT NULL,
        resume_path VARCHAR(255) DEFAULT NULL,
        cover_letter TEXT,
        experience TEXT,
        education TEXT,
        status VARCHAR(40) NOT NULL DEFAULT 'New',
        created_at DATETIME NOT NULL,
        CONSTRAINT fk_job FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

$seedUsers = [
    [
        'name' => 'TAASCOR Admin',
        'email' => 'admin@taascor.com',
        'role' => 'admin',
        'password' => 'Admin@123',
    ],
    [
        'name' => 'TAASCOR HR',
        'email' => 'hr@taascor.com',
        'role' => 'hr',
        'password' => 'Hr@123',
    ],
];

$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$insert = $pdo->prepare('INSERT INTO users (name, email, role, password_hash, created_at) VALUES (?, ?, ?, ?, NOW())');
foreach ($seedUsers as $seed) {
    $stmt->execute([$seed['email']]);
    if (!$stmt->fetch()) {
        $insert->execute([
            $seed['name'],
            $seed['email'],
            $seed['role'],
            password_hash($seed['password'], PASSWORD_BCRYPT)
        ]);
    }
}

echo 'Setup completed.';
