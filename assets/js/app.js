/* ============================================================
   SIMPUS-Mini — app.js
   Jobsheet 5: JavaScript DOM & Event
   ============================================================ */

/* ============================================================
   1. HAMBURGER MENU (JS-driven)
   ============================================================ */
function initNavToggle() {
  const toggleBtn = document.getElementById("nav-toggle-btn");
  const nav = document.querySelector("header nav");
  if (!toggleBtn || !nav) return;

  toggleBtn.addEventListener("click", function () {
    nav.classList.toggle("nav-open");
  });
}

/* ============================================================
   2. KONFIRMASI HAPUS
   ============================================================ */
function initHapusConfirm() {
  document.addEventListener("click", function (e) {
    // [Poin 26 - Praktik Tambahan: Inspeksi Event Delegation]
    console.log("[Event Delegation] Klik terdeteksi pada elemen:", e.target);

    const btn = e.target.closest(".btn-delete");
    if (!btn) return;

    console.log("[Event Delegation] Berhasil cocok dengan tombol .btn-delete:", btn);
    const row = btn.closest("tr");
    const name = row ? row.querySelector("td")?.textContent : "data ini";
    const yakin = confirm('Yakin ingin menghapus "' + name + '"?');
    if (yakin && row) {
      row.remove();
      const input = document.getElementById("search-input");
      if (input) input.dispatchEvent(new Event("keyup"));
    }
  });
}

/* ============================================================
   3. FILTER TABEL REAL-TIME
   ============================================================ */
function initTableFilter() {
  const input = document.getElementById("search-input");
  const table = document.querySelector(".table-responsive table");
  const counter = document.getElementById("result-counter");   // ← tambah
  if (!input || !table) return;

  function updateCounter() {
    if (!counter) return;
    const allRows = table.querySelectorAll("tbody tr");
    const visibleRows = Array.from(allRows).filter(function (row) {
      return row.style.display !== "none";
    });
    counter.textContent =
      "Menampilkan " + visibleRows.length + " dari " + allRows.length + " data.";
  }

  updateCounter();

  input.addEventListener("keyup", function () {
    const keyword = input.value.toLowerCase();
    const rows = table.querySelectorAll("tbody tr");
    rows.forEach(function (row) {
      const teks = row.textContent.toLowerCase();
      row.style.display = teks.includes(keyword) ? "" : "none";
    });
    updateCounter();   // ← update setelah filter
  });
}

/* ============================================================
   4. VALIDASI FORM (CLIENT-SIDE)
   ============================================================ */
function showError(input, message) {
  hapusError(input);
  const span = document.createElement("span");
  span.className = "error";
  span.textContent = message;
  input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
  const next = input.nextElementSibling;
  if (next && next.classList.contains("error")) {
    next.remove();
  }
}

function initValidasiForm() {
  const form = document.getElementById("form-tambah");
  if (!form) return;

  // ▼ Definisikan aturan validasi di array ▼
  const rules = [
    {
      selector: "[name='title'], [name='name']",
      validate: function (value) {
        return value.trim() !== "";
      },
      message: "Field ini wajib diisi."
    },
    {
      selector: "[name='author']",
      validate: function (value) {
        return value.trim() !== "";
      },
      message: "Pengarang wajib diisi."
    },
    {
      selector: "[name='year']",
      validate: function (value) {
        const n = parseInt(value, 10);
        return !isNaN(n) && n >= 1900 && n <= 2026;
      },
      message: "Tahun harus antara 1900-2026."
    },
    {
      selector: "[name='stock']",
      validate: function (value) {
        const n = parseInt(value, 10);
        return !isNaN(n) && n >= 0;
      },
      message: "Stok tidak boleh negatif."
    },
    {
      selector: "[name='no_anggota']",
      validate: function (value) {
        return value.trim() !== "";
      },
      message: "Member No wajib diisi."
    },
    {
      selector: "[name='isbn']",
      optional: true,                         // ← ISBN opsional
      validate: function (value) {
        if (value.trim() === "") return true; // kosong = valid (opsional)
        return /^[0-9-]+$/.test(value.trim());
      },
      message: "ISBN hanya boleh berisi angka dan tanda strip (-)."
    }
  ];

  form.addEventListener("submit", function (e) {
    let valid = true;

    rules.forEach(function (rule) {
      const input = form.querySelector(rule.selector);
      if (!input) return;   // field tidak ada di form ini → skip

      if (rule.validate(input.value)) {
        hapusError(input);
      } else {
        showError(input, rule.message);
        valid = false;
      }
    });

    if (!valid) {
      e.preventDefault();
    }
  });
}

/* ============================================================
   ENTRY POINT — Jalankan setelah DOM siap
   ============================================================ */
document.addEventListener("DOMContentLoaded", function () {
  initNavToggle();
  initHapusConfirm();
  initTableFilter();
  initValidasiForm();
});

/* ============================================================
   5. GENERIC TABLE LOADER (Poin 24 - Additional Practice)
   Fungsi generik untuk mengambil & menampilkan data tabel dari JSON
   ============================================================ */
async function loadTableData({
  url,
  tbodySelector = ".table-responsive table tbody",
  loadingId = "loading-indicator",
  columns = [],
  renderActions = null,
  delayMs = 600,
  emptyColspan = 5
}) {
  const tbody = document.querySelector(tbodySelector);
  const loading = document.getElementById(loadingId);
  if (!tbody) return;

  if (loading) loading.style.display = "block";
  tbody.innerHTML = "";

  try {
    // Simulasi delay jaringan (Poin 27 - delay dapat dikustomisasi)
    if (delayMs > 0) {
      await new Promise((resolve) => setTimeout(resolve, delayMs));
    }

    const res = await fetch(url);
    if (!res.ok) {
      throw new Error("Gagal mengambil data (status " + res.status + ")");
    }

    const dataList = await res.json();

    dataList.forEach(function (item) {
      const tr = document.createElement("tr");
      let html = "";
      columns.forEach(function (key) {
        html += "<td>" + (item[key] !== undefined ? item[key] : "") + "</td>";
      });

      if (typeof renderActions === "function") {
        html += "<td>" + renderActions(item) + "</td>";
      }

      tr.innerHTML = html;
      tbody.appendChild(tr);
    });

    const searchInput = document.getElementById("search-input");
    if (searchInput) {
      searchInput.dispatchEvent(new Event("keyup"));
    }
  } catch (err) {
    tbody.innerHTML =
      '<tr><td colspan="' + emptyColspan + '" style="text-align:center;">Gagal memuat data: ' +
      err.message +
      "</td></tr>";
  } finally {
    if (loading) loading.style.display = "none";
  }
}


