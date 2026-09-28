<?php
$page_title = "Daftar Anggota";
include __DIR__ . '/../includes/header.php';
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

require __DIR__ . '/../includes/koneksi.php';

// Jobsheet 9: Pagination & Server-Side Search
$perPage = 5;
$page = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    $hitung = $pdo->prepare("SELECT COUNT(*) FROM anggota WHERE nama ILIKE :kw OR no_anggota ILIKE :kw");
    $hitung->execute(['kw' => '%' . $keyword . '%']);
    $totalRows = (int) $hitung->fetchColumn();

    $stmt = $pdo->prepare(
        "SELECT * FROM anggota 
         WHERE nama ILIKE :kw OR no_anggota ILIKE :kw 
         ORDER BY id DESC 
         LIMIT :limit OFFSET :offset"
    );
    $stmt->bindValue('kw', '%' . $keyword . '%');
} else {
    $totalRows = (int) $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();

    $stmt = $pdo->prepare(
        "SELECT * FROM anggota 
         ORDER BY id DESC 
         LIMIT :limit OFFSET :offset"
    );
}

$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$daftarAnggota = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPages = max(1, (int) ceil($totalRows / $perPage));
?>
<section>
  <h2>Daftar Anggota</h2>
  <?php if ($flash): ?>
    <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></p>
  <?php endif; ?>

  <form method="get" action="list.php" class="search-box">
    <span>
      <label for="search-input">Cari Nama / No. Anggota</label><br>
      <input type="text" id="search-input" name="q" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="Ketik nama atau no. anggota...">
    </span>
    <button type="submit">Cari</button>
    <?php if ($keyword !== ''): ?>
      <a href="list.php" style="text-decoration: none; padding: 0.55rem 1rem; background: #6c757d; color: white; border-radius: 4px; font-size: 0.9rem;">Reset</a>
    <?php endif; ?>
  </form>

  <?php if ($keyword !== ''): ?>
    <p style="margin-top: 0.5rem; font-size: 0.9rem; color: #555;">
      Ditemukan <?php echo $totalRows; ?> anggota untuk pencarian "<strong><?php echo htmlspecialchars($keyword); ?></strong>"
    </p>
  <?php endif; ?>

  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th>No. Anggota</th>
          <th>Nama</th>
          <th>Alamat</th>
          <th>No. HP</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($daftarAnggota)): ?>
          <tr>
            <td colspan="5">
              <?php if ($keyword !== ''): ?>
                Tidak ada anggota yang sesuai dengan kata kunci "<?php echo htmlspecialchars($keyword); ?>".
              <?php else: ?>
                Belum ada data anggota. Silakan tambah melalui menu "Tambah Anggota".
              <?php endif; ?>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($daftarAnggota as $member): ?>
            <tr>
              <td><?php echo htmlspecialchars($member['no_anggota'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($member['nama'] ?? $member['name'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($member['alamat'] ?? $member['address'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($member['no_hp'] ?? ''); ?></td>
              <td>
                <a href="edit.php?id=<?php echo htmlspecialchars($member['id']); ?>" class="btn-edit">Edit</a>
                <form class="form-hapus" method="post" action="hapus.php">
                  <input type="hidden" name="id" value="<?php echo htmlspecialchars($member['id']); ?>">
                  <button type="submit" class="btn-delete">Hapus</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($totalPages > 1): ?>
    <nav class="pagination">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="list.php?page=<?php echo $i; ?><?php echo $keyword !== '' ? '&q=' . urlencode($keyword) : ''; ?>"
           class="<?php echo $i === $page ? 'active' : ''; ?>">
          <?php echo $i; ?>
        </a>
      <?php endfor; ?>
    </nav>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
