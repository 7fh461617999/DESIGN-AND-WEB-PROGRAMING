<?php
$page_title = "Edit Anggota";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM anggota WHERE id = :id");
$stmt->execute(['id' => $id]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Data anggota tidak ditemukan.'];
    header('Location: list.php');
    exit;
}
?>
<section>
  <h2>Edit Anggota</h2>
  <?php if ($flash): ?>
    <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></p>
  <?php endif; ?>

  <form id="form-edit" method="post" action="proses_edit.php">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($member['id']); ?>">
    <p>
      <label for="no_anggota">No. Anggota</label><br>
      <input type="text" id="no_anggota" name="no_anggota" value="<?php echo htmlspecialchars($member['no_anggota'] ?? ''); ?>" required>
    </p>
    <p>
      <label for="name">Nama</label><br>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($member['nama'] ?? $member['name'] ?? ''); ?>" required>
    </p>
    <p>
      <label for="address">Alamat</label><br>
      <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($member['alamat'] ?? $member['address'] ?? ''); ?>" required>
    </p>
    <p>
      <label for="no_hp">No. HP</label><br>
      <input type="text" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($member['no_hp'] ?? ''); ?>" required>
    </p>
    <p>
      <button type="submit">Simpan Perubahan</button>
      <a href="list.php" style="margin-left: 0.5rem; text-decoration: none; color: #555;">Batal</a>
    </p>
  </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
