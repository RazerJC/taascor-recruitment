<?php
/**
 * Migration script: seeds Render PostgreSQL with local MySQL data.
 * Visit this URL ONCE after setup.php to import data: /seed_data.php
 */
require_once __DIR__ . '/lib/db.php';

$pdo = db();
$output = [];

// --- USERS ---
$users = [
    ['name' => 'TAASCOR Admin', 'email' => 'admin@taascor.com', 'role' => 'admin', 'password_hash' => '$2y$10$H3ub2XKxJD2AQXL3yzgzG.kJs7ymonRWW/sTF2pra82Wr.8pzxTgK', 'created_at' => '2026-02-23 11:15:13'],
    ['name' => 'TAASCOR HR', 'email' => 'hr@taascor.com', 'role' => 'hr', 'password_hash' => '$2y$10$T4Z0mZyAOKwJ1pDdZ/c0FOE/SymLbWeivHDhep9WcGYK2EDrQisa2', 'created_at' => '2026-02-23 11:15:14'],
    ['name' => 'HR MARIANNE', 'email' => 'marianepabilan@gmail.com', 'role' => 'hr', 'password_hash' => '$2y$10$PyP4a4c4.qbwh9ce9Cxh4eZ6wwVj2/bgTQ5FbHJEvwndYY/Mpkmbe', 'created_at' => '2026-02-26 16:38:27'],
];

$checkUser = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$insertUser = $pdo->prepare('INSERT INTO users (name, email, role, password_hash, created_at) VALUES (?, ?, ?, ?, ?)');
foreach ($users as $u) {
    $checkUser->execute([$u['email']]);
    if (!$checkUser->fetch()) {
        $insertUser->execute([$u['name'], $u['email'], $u['role'], $u['password_hash'], $u['created_at']]);
        $output[] = "User added: " . $u['email'];
    } else {
        $output[] = "User exists: " . $u['email'];
    }
}

// --- JOBS ---
$jobs = [
    [
        'title' => 'MULTIMIX',
        'location' => 'TERELAY',
        'employment_type' => 'PRODUCTION WORKER',
        'short_description' => 'PACKER / SORTER /',
        'requirements' => "Standard Pre-Employment Documents \nPSA Birth Certificate: Original and photocopy (must be from the Philippine Statistics Authority).\nResume or Curriculum Vitae (CV): Updated.\nDiploma and Transcript of Records (TOR): Copy of college or high school diploma.\nCertificate of Employment (COE): From previous employers, specifically for experienced hires.\nNBI Clearance: Original with a dry seal or verifiable online (must be recent, usually valid for 6 months).\nPolice Clearance: Often required as a local-level background check.\nBarangay Clearance: Often needed for residency verification.",
        'is_active' => 1,
        'created_at' => '2026-02-24 10:20:21',
        'company_choice' => 'MULTIMIX — Terelay Industrial Estate, Bo. Pittland, Provincial Rd, Cabuyao City, Laguna',
    ],
    [
        'title' => 'SORTER',
        'location' => 'BATINO',
        'employment_type' => 'Contract',
        'short_description' => 'SORTER / PICKER / PACKER',
        'requirements' => "Standard Pre-Employment Documents \nPSA Birth Certificate: Original and photocopy (must be from the Philippine Statistics Authority).\nResume or Curriculum Vitae (CV): Updated.\nDiploma and Transcript of Records (TOR): Copy of college or high school diploma.\nCertificate of Employment (COE): From previous employers, specifically for experienced hires.\nNBI Clearance: Original with a dry seal or verifiable online (must be recent, usually valid for 6 months).\nPolice Clearance: Often required as a local-level background check.\nBarangay Clearance: Often needed for residency verification.",
        'is_active' => 1,
        'created_at' => '2026-02-24 10:21:32',
        'company_choice' => 'SHOPEE — Batino Warehouse, Exit, Calamba, 4027 Laguna',
    ],
    [
        'title' => 'PICKER',
        'location' => 'BATINO',
        'employment_type' => 'Full-time',
        'short_description' => 'SORTER / PICKER / PACKER',
        'requirements' => '',
        'is_active' => 1,
        'created_at' => '2026-02-24 13:49:33',
        'company_choice' => 'SHOPEE — Batino Warehouse, Exit, Calamba, 4027 Laguna',
    ],
    [
        'title' => 'PACKER',
        'location' => 'BATINO',
        'employment_type' => 'Full-time',
        'short_description' => 'SHIT WORK',
        'requirements' => 'ALL OF YOUR DOCUMENTS SINCE BIRTH',
        'is_active' => 1,
        'created_at' => '2026-02-24 13:50:54',
        'company_choice' => 'SHOPEE — Batino Warehouse, Exit, Calamba, 4027 Laguna',
    ],
];

$checkJob = $pdo->prepare('SELECT id FROM jobs WHERE title = ? AND created_at = ?');
$insertJob = $pdo->prepare('INSERT INTO jobs (title, location, employment_type, short_description, requirements, is_active, created_at, company_choice) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
$jobIdMap = [];
foreach ($jobs as $i => $j) {
    $checkJob->execute([$j['title'], $j['created_at']]);
    $existing = $checkJob->fetch();
    if (!$existing) {
        $insertJob->execute([$j['title'], $j['location'], $j['employment_type'], $j['short_description'], $j['requirements'], $j['is_active'], $j['created_at'], $j['company_choice']]);
        $jobIdMap[$i + 1] = $pdo->lastInsertId();
        $output[] = "Job added: " . $j['title'];
    } else {
        $jobIdMap[$i + 1] = $existing['id'];
        $output[] = "Job exists: " . $j['title'];
    }
}

// --- APPLICATIONS ---
$applications = [
    [
        'name' => 'John Carl BANARES',
        'email' => 'banaresjohncarl30@gmail.com',
        'phone' => '09954022472',
        'address' => 'PULO',
        'job_id_ref' => 2,
        'company_choice' => 'SHOPEE — Batino Warehouse, Exit, Calamba, 4027 Laguna',
        'resume_path' => null,
        'cover_letter' => 'asd',
        'experience' => 'sadas',
        'education' => 'sadas',
        'status' => 'New',
        'created_at' => '2026-02-24 10:28:24',
        'form_data' => null,
    ],
    [
        'name' => 'BANARES, John Carl',
        'email' => 'banaresjohncarl30@gmail.com',
        'phone' => '09954022472',
        'address' => 'PULO',
        'job_id_ref' => 1,
        'company_choice' => 'SHOPEE — Batino Warehouse, Exit, Calamba, 4027 Laguna',
        'resume_path' => null,
        'cover_letter' => '',
        'experience' => '',
        'education' => '',
        'status' => 'Rejected',
        'created_at' => '2026-02-24 13:11:41',
        'form_data' => '{"ref_1_name":"","ref_1_address":"","ref_1_occupation":"","ref_2_name":"","ref_2_address":"","ref_2_occupation":"","ref_3_name":"","ref_3_address":"","ref_3_occupation":"","elem_school":"","elem_year":"","hs_school":"","hs_year":"","college_course":"","college_school":"","college_year":"","vocational_course":"","vocational_school":"","vocational_year":"","employment_1_from":"","employment_1_to":"","employment_1_position":"","employment_1_company":"","employment_2_from":"","employment_2_to":"","employment_2_position":"","employment_2_company":"","employment_3_from":"","employment_3_to":"","employment_3_position":"","employment_3_company":"","sss_no":"","tin_no":"","philhealth_no":"","pagibig_no":"","phil_id_no":"","father_name":"","father_occupation":"","mother_name":"","mother_occupation":"","language_dialect":"","emergency_person":"","emergency_address":"","emergency_contact":"","last_name":"BANARES","first_name":"John Carl","middle_name":"","present_address":"PULO","email_address":"banaresjohncarl30@gmail.com","phone":"09954022472","citizenship":"filipinO","date_of_birth":"2006-06-10","place_of_birth":"CALAMBA","civil_status":"Single","mothers_maiden_name":"","sex":"","height":"","weight":"","religion":"","spouse_name":"","spouse_address":"","spouse_occupation":""}',
    ],
    [
        'name' => 'GUITEREEZ, CRJUSEFINV GERLAD',
        'email' => 'VIN123@gmail.com',
        'phone' => '09123456878',
        'address' => 'PULO',
        'job_id_ref' => 4,
        'company_choice' => 'SHOPEE — Batino Warehouse, Exit, Calamba, 4027 Laguna',
        'resume_path' => null,
        'cover_letter' => '',
        'experience' => '',
        'education' => '',
        'status' => 'Under Review',
        'created_at' => '2026-02-26 16:34:18',
        'form_data' => '{"ref_1_name":"jc","ref_1_address":"mayapa","ref_1_occupation":"ceo","ref_2_name":"pongs","ref_2_address":"dizeomo","ref_2_occupation":"ceo","ref_3_name":"aves","ref_3_address":"pulo","ref_3_occupation":"adik","elem_school":"MAMatid es","elem_year":"2005","hs_school":"MAMatid hs","hs_year":"2010","college_course":"BSIT","college_school":"SVCC","college_year":"2027","vocational_course":"WALA","vocational_school":"WALA","vocational_year":"WALA","employment_1_from":"n/A","employment_1_to":"n/A","employment_1_position":"n/A","employment_1_company":"n/A","employment_2_from":"n/A","employment_2_to":"n/A","employment_2_position":"n/A","employment_2_company":"n/A","employment_3_from":"n/A","employment_3_to":"n/A","employment_3_position":"n/A","employment_3_company":"n/A","sss_no":"super ss 2","tin_no":"wala","philhealth_no":"wala","pagibig_no":"wawlalaw","phil_id_no":"wala","father_name":"naruto umuzaki","father_occupation":"hokage","mother_name":"hinata","mother_occupation":"housewife","language_dialect":"janapense chinese all of lanauge","emergency_person":"goku","emergency_address":"911","emergency_contact":"911","last_name":"GUITEREEZ","first_name":"CRJUSEFINV","middle_name":"GERLAD","present_address":"PULO","email_address":"VIN123@gmail.com","phone":"09123456878","citizenship":"filipinO","date_of_birth":"2002-12-25","place_of_birth":"CALAMBA","civil_status":"Married","mothers_maiden_name":"BULMA SON GOKU","sex":"Female","height":"200cm","weight":"90kg","religion":"CATHLOIC","spouse_name":"ANGELA","spouse_address":"MAMATID","spouse_occupation":"HOUSEWIFE"}',
    ],
];

$insertApp = $pdo->prepare('INSERT INTO applications (name, email, phone, address, job_id, company_choice, resume_path, cover_letter, experience, education, status, created_at, form_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$checkApp = $pdo->prepare('SELECT id FROM applications WHERE email = ? AND created_at = ?');
foreach ($applications as $a) {
    $checkApp->execute([$a['email'], $a['created_at']]);
    if (!$checkApp->fetch()) {
        $jobId = $jobIdMap[$a['job_id_ref']] ?? null;
        $insertApp->execute([$a['name'], $a['email'], $a['phone'], $a['address'], $jobId, $a['company_choice'], $a['resume_path'], $a['cover_letter'], $a['experience'], $a['education'], $a['status'], $a['created_at'], $a['form_data']]);
        $output[] = "Application added: " . $a['name'];
    } else {
        $output[] = "Application exists: " . $a['name'];
    }
}

echo "<h2>Migration Results</h2><pre>" . implode("\n", $output) . "</pre>";
