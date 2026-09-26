/* ============================================================
   Fetch & tampilkan Daftar Anggota dari data/anggota.json
   Jobsheet 6: Fetch API & JSON (Tercakup Praktik Tambahan 23, 24, 27)
   ============================================================ */

async function muatDaftarAnggota() {
  // Poin 24: Menggunakan fungsi generik loadTableData() dari app.js
  await loadTableData({
    url: "../data/anggota.json",
    columns: ["no_anggota", "name", "address", "no_hp"],
    emptyColspan: 5,
    delayMs: 600,
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
  muatDaftarAnggota();

  // Poin 23: Event listener untuk tombol "Muat Ulang" (Reload)
  const btnReload = document.getElementById("btn-reload");
  if (btnReload) {
    btnReload.addEventListener("click", function () {
      muatDaftarAnggota();
    });
  }
});
