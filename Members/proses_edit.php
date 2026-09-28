<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$id = $_POST['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

$no_anggota = trim($_POST['no_anggota'] ?? '');
$name       = trim($_POST['name'] ?? '');
$address    = trim($_POST['address'] ?? '');
$no_hp      = trim($_POST['no_hp'] ?? '');

$errors = [];
if ($no_anggota === '') {
    $errors[] = "Nomor Anggota wajib diisi.";
} else {
    $check = $pdo->prepare("SELECT 1 FROM anggota WHERE no_anggota = :no_anggota AND id != :id");
    $check->execute(['no_anggota' => $no_anggota, 'id' => $id]);
    if ($check->fetch()) {
        $errors[] = "Nomor Anggota '{$no_anggota}' sudah terdaftar pada anggota lain.";
    }
}

if ($name === '') {
    $errors[] = "Nama wajib diisi.";
} elseif (strlen($name) < 3) {
    $errors[] = "Nama minimal harus 3 karakter.";
}

if ($address === '') {
    $errors[] = "Alamat wajib diisi.";
}

if ($no_hp === '') {
    $errors[] = "No. HP wajib diisi.";
} elseif (!preg_match('/^[0-9+ -]{9,15}$/', $no_hp)) {
    $errors[] = "No. HP harus berisi 9-15 digit angka yang valid.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => implode(' ', $errors)];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

try {
    $stmt = $pdo->prepare(
        "UPDATE anggota 
         SET no_anggota = :no_anggota, nama = :nama, alamat = :alamat, no_hp = :no_hp 
         WHERE id = :id"
    );
    $stmt->execute([
        'no_anggota' => $no_anggota,
        'nama'       => $name,
        'alamat'     => $address,
        'no_hp'      => $no_hp,
        'id'         => (int) $id,
    ]);
} catch (PDOException $e) {
    if ($e->getCode() == 23505) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => "Nomor Anggota sudah digunakan, gunakan nomor lain."];
    } else {
        $_SESSION['flash'] = ['type' => 'error', 'message' => "Gagal memperbarui data: " . $e->getMessage()];
    }
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

$_SESSION['flash'] = ['type' => 'success', 'message' => 'Data anggota berhasil diperbarui.'];
header('Location: list.php');
exit;
