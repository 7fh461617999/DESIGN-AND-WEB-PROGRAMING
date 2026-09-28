<?php
session_start();
$__jobsheetRoot = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__jobsheetRoot))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIMPUS-Mini<?php echo isset($page_title) ? ' | ' . $page_title : ''; ?></title>
  <link rel="stylesheet" href="<?php echo $base; ?>assets/CSS/style.css">
</head>
<body>
  <header>
    <h1>SIMPUS-Mini</h1>
    <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu">&#9776;</button>
    <nav>
      <ul>
        <li><a href="<?php echo $base; ?>index.php">Beranda</a></li>
        <li><a href="<?php echo $base; ?>Books/list.php">Daftar Buku</a></li>
        <li><a href="<?php echo $base; ?>Books/tambah.php">Tambah Buku</a></li>
        <li><a href="<?php echo $base; ?>Members/list.php">Daftar Anggota</a></li>
        <li><a href="<?php echo $base; ?>Members/tambah.php">Tambah Anggota</a></li>
      </ul>
    </nav>
  </header>
  <main>
