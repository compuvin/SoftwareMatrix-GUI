<?php
session_start();

require_once __DIR__ . '/auth-config.php';
require_once __DIR__ . '/login.php';

function auth_current_path(): string
{
    return $_SERVER['REQUEST_URI'] ?? '/';
}

function auth_safe_redirect(string $path): string
{
    if ($path === '') {
        return '/';
    }

    if (preg_match('/^https?:\\/\\//i', $path)) {
        return '/';
    }

    if ($path[0] !== '/') {
        $path = '/' . ltrim($path, '/');
    }

    return $path;
}

function auth_db_connect(): mysqli
{
    global $host, $user, $pass, $db_name;

    $connection = mysqli_connect($host, $user, $pass, $db_name);

    if (mysqli_connect_errno()) {
        die("connection failed: "
            . mysqli_connect_error()
            . " (" . mysqli_connect_errno()
            . ")");
    }

    return $connection;
}

function auth_initialize(mysqli $connection): void
{
    $createSql = "CREATE TABLE IF NOT EXISTS auth_users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(100) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    mysqli_query($connection, $createSql);

    $result = mysqli_query($connection, "SELECT COUNT(*) AS count_admins FROM auth_users WHERE role = 'admin'");
    if ($result === false) {
        return;
    }

    $row = mysqli_fetch_assoc($result);
    $adminCount = (int) ($row['count_admins'] ?? 0);

    if ($adminCount > 0) {
        return;
    }

    global $AUTH_DEFAULT_ADMIN;
    $username = $AUTH_DEFAULT_ADMIN['username'] ?? 'admin';
    $passwordHash = $AUTH_DEFAULT_ADMIN['password_hash'] ?? '';

    if ($passwordHash === '') {
        return;
    }

    $statement = mysqli_prepare(
        $connection,
        "INSERT INTO auth_users (username, password_hash, role) VALUES (?, ?, 'admin')"
    );

    if ($statement === false) {
        return;
    }

    mysqli_stmt_bind_param($statement, 'ss', $username, $passwordHash);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);
}

function auth_login(string $username, string $password): bool
{
    $connection = auth_db_connect();
    auth_initialize($connection);

    $statement = mysqli_prepare(
        $connection,
        "SELECT username, password_hash, role FROM auth_users WHERE username = ? LIMIT 1"
    );

    if ($statement === false) {
        mysqli_close($connection);
        return false;
    }

    mysqli_stmt_bind_param($statement, 's', $username);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $row = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($statement);
    mysqli_close($connection);

    if (!$row) {
        return false;
    }

    if (!password_verify($password, $row['password_hash'])) {
        return false;
    }

    auth_log_in_user($row['username'], $row['role']);
    return true;
}

function auth_log_in_user(string $username, string $role = 'user'): void
{
    $_SESSION['auth_user'] = $username;
    $_SESSION['auth_role'] = $role;
    session_regenerate_id(true);
}

function auth_log_out_user(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

function require_admin(): void
{
    if (!empty($_SESSION['auth_user']) && ($_SESSION['auth_role'] ?? '') === 'admin') {
        return;
    }

    $redirect = auth_safe_redirect(auth_current_path());
    header('Location: auth-login.php?redirect=' . urlencode($redirect));
    exit;
}

function require_auth(): void
{
    if (!empty($_SESSION['auth_user'])) {
        return;
    }

    $redirect = auth_safe_redirect(auth_current_path());
    header('Location: auth-login.php?redirect=' . urlencode($redirect));
    exit;
}
