<?php
$page_title = "Beranda";
include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/koneksi.php';
$totalBuku = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
$totalAnggota = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<?php if ($flash): ?>
  <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['message']; ?></p>
<?php endif; ?>
<section>
  <h2>Selamat Datang di Sistem Perpustakaan Mini</h2>
  <p>Aplikasi sederhana untuk mengelola data buku dan anggota perpustakaan.</p>
</section>

<section>
  <h2>Ringkasan</h2>
  <section class="stat-grid">
    <article>
      <h3>Total Buku</h3>
      <p><?php echo $totalBuku; ?></p>
    </article>
    <article>
      <h3>Total Anggota</h3>
      <p><?php echo $totalAnggota; ?></p>
    </article>
    <article>
      <h3>Sedang Dipinjam</h3>
      <p>3</p>
    </article>
    <article>
      <h3>Total Hilang</h3>
      <p>1</p>
    </article>
  </section>
  <div style="margin-top: 1.5rem; display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
    <form method="post" action="reset_session.php" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua data sesi?');">
      <button type="submit" style="background-color: #d9534f; color: #fff; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; font-weight: bold;">
         Reset Data Sesi
      </button>
    </form>   
  </div>
</section>

<section>
  <h2>Contoh Kode CSS</h2>
  <div class="table-responsive">
    <pre><code>
.stat-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
  background: linear-gradient(135deg, #1e6b3a 0%, #154d29 100%);
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}</code></pre>
  </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
