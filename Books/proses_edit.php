<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$id = $_POST['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

$judul     = trim($_POST['title'] ?? '');
$pengarang = trim($_POST['author'] ?? '');
$tahun     = $_POST['year'] ?? '';
$isbn      = trim($_POST['isbn'] ?? '');
$stok      = $_POST['stock'] ?? '';

$errors = [];
if ($judul === '') {
    $errors[] = "Judul wajib diisi.";
}
if ($pengarang === '') {
    $errors[] = "Pengarang wajib diisi.";
}
if (!is_numeric($tahun) || $tahun < 1900 || $tahun > 2026) {
    $errors[] = "Tahun harus antara 1900-2026.";
}
if (!is_numeric($stok) || $stok < 0) {
    $errors[] = "Stok tidak boleh negatif.";
}
if ($isbn !== '' && !preg_match('/^[0-9-]+$/', $isbn)) {
    $errors[] = "ISBN hanya boleh berisi angka dan tanda hubung (-).";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $errors)];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

$stmt = $pdo->prepare(
    "UPDATE buku 
     SET judul = :judul, pengarang = :pengarang, tahun = :tahun, isbn = :isbn, stok = :stok 
     WHERE id = :id"
);
$stmt->execute([
    'judul'     => $judul,
    'pengarang' => $pengarang,
    'tahun'     => (int) $tahun,
    'isbn'      => $isbn,
    'stok'      => (int) $stok,
    'id'        => (int) $id,
]);

$_SESSION['flash'] = ['type' => 'success', 'message' => 'Buku berhasil diperbarui.'];
header('Location: list.php');
exit;
