/* ============================================================
   Fetch & tampilkan Daftar Buku dari data/buku.json
   Jobsheet 6: Fetch API & JSON (Tercakup Praktik Tambahan 23, 24, 25, 27)
   ============================================================ */

async function muatDaftarBuku() {
  await loadTableData({
    url: "../data/buku.json",
    columns: ["title", "author", "year", "category", "stock"],
    emptyColspan: 6,
    delayMs: 3000,
    renderActions: function () {
      return (
        '<button type="button">Edit</button> ' +
        '<button type="button" class="btn-delete">Hapus</button>'
      );
    }
  });
}

// Inisialisasi saat DOM siap
document.addEventListener("DOMContentLoaded", function () {
  muatDaftarBuku();

  // Poin 23: Event listener untuk tombol "Muat Ulang" (Reload)
  const btnReload = document.getElementById("btn-reload");
  if (btnReload) {
    btnReload.addEventListener("click", function () {
      muatDaftarBuku();
    });
  }
});
