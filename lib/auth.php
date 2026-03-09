<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

function auth_start()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function current_user()
{
    auth_start();
    return $_SESSION['user'] ?? null;
}

function login($email, $password)
{
    auth_start();
    $stmt = db()->prepare('SELECT id, name, email, role, password_hash FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) {
        return false;
    }
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
    ];
    return true;
}

function logout()
{
    auth_start();
    $_SESSION = [];
    session_destroy();
}

function require_login()
{
    if (!current_user()) {
        redirect('/login.php');
    }
}

function require_role($role)
{
    require_login();
    $user = current_user();
    if (!$user || $user['role'] !== $role) {
        redirect('/login.php');
    }
}

function require_any_role($roles)
{
    require_login();
    $user = current_user();
    if (!$user || !in_array($user['role'], $roles, true)) {
        redirect('/login.php');
    }
}
