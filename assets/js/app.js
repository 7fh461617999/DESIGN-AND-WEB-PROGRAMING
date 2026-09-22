function initNavToggle() {
  const toggleBtn = document.getElementById("nav-toggle-btn");
  const nav = document.querySelector("header nav");
  if (!toggleBtn || !nav) return;

  toggleBtn.addEventListener("click", function () {
    nav.classList.toggle("nav-open");
  });
}
function initHapusConfirm() {
  document.querySelectorAll(".btn-delete").forEach(function (btn) {
    btn.addEventListener("click", function () {
      const row = btn.closest("tr");
      const name = row ? row.querySelector("td")?.textContent : "data ini";
      const yakin = confirm('Yakin ingin menghapus "' + name + '"?');
      if (yakin && row) {
        row.remove();
      }
    });
  });
}
function initTableFilter() {
  const input = document.getElementById("search-input");
  const table = document.querySelector(".table-responsive table");
  if (!input || !table) return;

  input.addEventListener("keyup", function () {
    const keyword = input.value.toLowerCase();
    const rows = table.querySelectorAll("tbody tr");
    rows.forEach(function (row) {
      const teks = row.textContent.toLowerCase();
      row.style.display = teks.includes(keyword) ? "" : "none";
    });
  });
}
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

  form.addEventListener("submit", function (e) {
    let valid = true;

    const judul = form.querySelector("[name='title'], [name='name']");
    if (judul && judul.value.trim() === "") {
      showError(judul, "Field ini wajib diisi.");
      valid = false;
    } else if (judul) {
      hapusError(judul);
    }

    const tahun = form.querySelector("[name='year']");
    if (tahun) {
      const value = parseInt(tahun.value, 10);
      if (isNaN(value) || value < 1900 || value > 2026) {
        showError(tahun, "Tahun harus antara 1900-2026.");
        valid = false;
      } else {
        hapusError(tahun);
      }
    }

    if (!valid) {
      e.preventDefault();
    }
  });
}
