<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

// Hanya terima method POST untuk keamanan data
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM anggota WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data anggota berhasil dihapus.'];
}

header('Location: list.php');
exit;
