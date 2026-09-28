<?php
$page_title = "Tambah Buku";
include __DIR__ . '/../includes/header.php';
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<section>
  <h2>Tambah Buku</h2>
  <?php if ($flash): ?>
    <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></p>
  <?php endif; ?>
  <form id="form-tambah" method="post" action="proses_tambah.php">
    <p>
      <label for="title">Judul Buku</label><br>
      <input type="text" id="title" name="title">
    </p>
    <p>
      <label for="author">Pengarang</label><br>
      <input type="text" id="author" name="author">
    </p>
    <p>
      <label for="year">Tahun Terbit</label><br>
      <input type="number" id="year" name="year">
    </p>
    <p>
      <label for="isbn">ISBN</label><br>
      <input type="text" id="isbn" name="isbn">
    </p>
    <p>
      <label for="stock">Jumlah Stok</label><br>
      <input type="number" id="stock" name="stock">
    </p>
    <p>
      <button type="submit">Simpan</button>
    </p>
  </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
