<?php
$page_title = "Edit Buku";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id");
$stmt->execute(['id' => $id]);
$buku = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$buku) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Data buku tidak ditemukan.'];
    header('Location: list.php');
    exit;
}
?>
<section>
  <h2>Edit Buku</h2>
  <?php if ($flash): ?>
    <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></p>
  <?php endif; ?>

  <form id="form-edit" method="post" action="proses_edit.php">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($buku['id']); ?>">
    <p>
      <label for="title">Judul Buku</label><br>
      <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($buku['judul'] ?? ''); ?>" required>
    </p>
    <p>
      <label for="author">Pengarang</label><br>
      <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($buku['pengarang'] ?? ''); ?>" required>
    </p>
    <p>
      <label for="year">Tahun Terbit</label><br>
      <input type="number" id="year" name="year" value="<?php echo htmlspecialchars($buku['tahun'] ?? ''); ?>" required>
    </p>
    <p>
      <label for="isbn">ISBN</label><br>
      <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($buku['isbn'] ?? ''); ?>">
    </p>
    <p>
      <label for="stock">Jumlah Stok</label><br>
      <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($buku['stok'] ?? ''); ?>" required>
    </p>
    <p>
      <button type="submit">Simpan Perubahan</button>
      <a href="list.php" style="margin-left: 0.5rem; text-decoration: none; color: #555;">Batal</a>
    </p>
  </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
