<?php
/**
 * Jobsheet 8 - Latihan 4 (§7.4 Poin 28)
 * Script Migrasi Data Buku dari data/buku.json ke Database PostgreSQL
 */

require_once __DIR__ . '/includes/koneksi.php';

$jsonPath = __DIR__ . '/data/buku.json';

if (!file_exists($jsonPath)) {
    die("File data/buku.json tidak ditemukan!");
}

$jsonData = file_get_contents($jsonPath);
$books = json_decode($jsonData, true);

if (!is_array($books)) {
    die("Format file data/buku.json tidak valid!");
}

$stmtCheck = $pdo->prepare("SELECT 1 FROM buku WHERE judul = :judul AND pengarang = :pengarang");
$stmtInsert = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, stok) 
     VALUES (:judul, :pengarang, :tahun, :stok) 
     RETURNING id"
);

$insertedCount = 0;
$skippedCount = 0;
$logs = [];

foreach ($books as $item) {
    $judul     = trim($item['title'] ?? '');
    $pengarang = trim($item['author'] ?? '');
    $tahun     = (int) ($item['year'] ?? 0);
    $stok      = (int) ($item['stock'] ?? 0);

    // Cek apakah buku sudah ada agar idempotent (tidak duplikat jika dijalankan ulang)
    $stmtCheck->execute([
        'judul'     => $judul,
        'pengarang' => $pengarang
    ]);

    if ($stmtCheck->fetch()) {
        $skippedCount++;
        $logs[] = "⏭️ Lewati (sudah ada): {$judul} - {$pengarang}";
        continue;
    }

    $stmtInsert->execute([
        'judul'     => $judul,
        'pengarang' => $pengarang,
        'tahun'     => $tahun,
        'stok'      => $stok,
    ]);

    $insertedCount++;
    $logs[] = "✅ Berhasil ditambahkan: {$judul} ({$tahun}) - {$pengarang}";
}

$isCli = (php_sapi_name() === 'cli');

if ($isCli) {
    echo "=== HASIL MIGRASI DATA BUKU ===\n";
    foreach ($logs as $log) {
        echo $log . "\n";
    }
    echo "---------------------------------\n";
    echo "Total buku berhasil diimpor : {$insertedCount}\n";
    echo "Total buku dilewati (duplikat): {$skippedCount}\n";
    echo "=================================\n";
} else {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
      <meta charset="UTF-8">
      <title>Migrasi Data Buku - Jobsheet 8</title>
      <style>
        body { font-family: system-ui, sans-serif; max-width: 700px; margin: 2rem auto; padding: 1rem; line-height: 1.6; }
        .card { background: #fdfdfd; border: 1px solid #ddd; border-radius: 8px; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h1 { color: #1e6b3a; margin-top: 0; }
        ul { background: #f8f9fa; border: 1px solid #eee; border-radius: 6px; padding: 1rem 1.5rem; list-style: none; }
        li { margin-bottom: 0.4rem; }
        .btn { display: inline-block; background: #1e6b3a; color: white; padding: 0.6rem 1.2rem; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 1rem; }
      </style>
    </head>
    <body>
      <div class="card">
        <h1>📦 Migrasi Data Selesai</h1>
        <p><strong><?php echo $insertedCount; ?></strong> buku baru berhasil diimpor, <strong><?php echo $skippedCount; ?></strong> buku dilewati.</p>
        <ul>
          <?php foreach ($logs as $log): ?>
            <li><?php echo htmlspecialchars($log); ?></li>
          <?php endforeach; ?>
        </ul>
        <a href="Books/list.php" class="btn">Lihat Daftar Buku &rarr;</a>
      </div>
    </body>
    </html>
    <?php
}
