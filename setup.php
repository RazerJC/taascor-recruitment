<?php
require_once __DIR__ . '/lib/db.php';

$pdo = db();

$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        role VARCHAR(10) NOT NULL DEFAULT 'hr',
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT NOW()
    );
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS jobs (
        id SERIAL PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        location VARCHAR(200) NOT NULL,
        employment_type VARCHAR(80) NOT NULL,
        short_description VARCHAR(255) DEFAULT '',
        requirements TEXT,
        is_active SMALLINT NOT NULL DEFAULT 1,
        created_at TIMESTAMP NOT NULL DEFAULT NOW()
    );
");

$pdo->exec("
    DO \$\$ BEGIN
        IF NOT EXISTS (
            SELECT 1 FROM information_schema.table_constraints
            WHERE constraint_name = 'fk_job' AND table_name = 'applications'
        ) THEN
            CREATE TABLE IF NOT EXISTS applications (
                id SERIAL PRIMARY KEY,
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
                created_at TIMESTAMP NOT NULL DEFAULT NOW(),
                CONSTRAINT fk_job FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE SET NULL
            );
        END IF;
    END \$\$;
");

// Also create without the DO block as fallback
$pdo->exec("
    CREATE TABLE IF NOT EXISTS applications (
        id SERIAL PRIMARY KEY,
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
        created_at TIMESTAMP NOT NULL DEFAULT NOW()
    );
");

// Add foreign key if not exists
try {
    $pdo->exec("ALTER TABLE applications ADD CONSTRAINT fk_job FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE SET NULL;");
}
catch (Throwable $e) {
// Constraint already exists, ignore
}

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
