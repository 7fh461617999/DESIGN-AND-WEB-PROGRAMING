<?php
session_start();

// Kosongkan semua data session
$_SESSION = [];

// Hapus cookie sesi dari browser jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Hancurkan session di server
session_destroy();

// Mulai session baru khusus untuk menyimpan notifikasi flash sukses
session_start();
$_SESSION['flash'] = [
    'type' => 'success',
    'message' => 'Semua data sesi berhasil di-reset.'
];

header('Location: index.php');
exit;
