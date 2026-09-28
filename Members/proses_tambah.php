<?php
session_start();

$no_anggota = trim($_POST['no_anggota'] ?? '');
$name = trim($_POST['name'] ?? '');
$address = trim($_POST['address'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');

require __DIR__ . '/../includes/koneksi.php';

$errors = [];
if ($no_anggota === '') {
    $errors[] = "Nomor Anggota wajib diisi.";
} else {
    $check = $pdo->prepare("SELECT 1 FROM anggota WHERE no_anggota = :no_anggota");
    $check->execute(['no_anggota' => $no_anggota]);
    if ($check->fetch()) {
        $errors[] = "Nomor Anggota '{$no_anggota}' sudah terdaftar.";
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
    header('Location: tambah.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO anggota (nama, no_anggota, alamat, no_hp)
         VALUES (:nama, :no_anggota, :alamat, :no_hp)
         RETURNING id"
    );
    $stmt->execute([
        'nama'       => $name,
        'no_anggota' => $no_anggota,
        'alamat'     => $address,
        'no_hp'      => $no_hp,
    ]);
} catch (PDOException $e) {
    // 7.4 Latihan 1: Tangani error UNIQUE constraint secara rapi
    if ($e->getCode() == 23505 || (isset($e->errorInfo[0]) && $e->errorInfo[0] == '23505')) {
        $_SESSION['flash'] = [
            'type'    => 'error',
            'message' => "Nomor Anggota sudah digunakan, gunakan nomor lain."
        ];
    } else {
        $_SESSION['flash'] = [
            'type'    => 'error',
            'message' => "Terjadi kesalahan database: " . $e->getMessage()
        ];
    }
    header('Location: tambah.php');
    exit;
}

$_SESSION['flash'] = ['type' => 'success', 'message' => 'Anggota berhasil ditambahkan.'];
header('Location: list.php');
exit;
