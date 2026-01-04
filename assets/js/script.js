// Fungsi-fungsi Modal
function openModal(modalId) {
  document.getElementById(modalId).style.display = "block";
  document.body.style.overflow = "hidden";
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
  document.body.style.overflow = "auto";
}

// Menutup modal saat mengklik area di luar modal
window.onclick = function (event) {
  if (event.target.classList.contains("modal")) {
    event.target.style.display = "none";
    document.body.style.overflow = "auto";
  }
};

// Menutup modal dengan tombol ESC
document.addEventListener("keydown", function (event) {
  if (event.key === "Escape") {
    const modals = document.querySelectorAll(".modal");
    modals.forEach((modal) => {
      modal.style.display = "none";
    });
    document.body.style.overflow = "auto";
  }
});

// Konfirmasi Hapus
function confirmDelete(message = "Yakin ingin menghapus data ini?") {
  return confirm(message);
}

// Validasi Formulir
function validateForm(formId) {
  const form = document.getElementById(formId);
  if (!form) return false;

  const inputs = form.querySelectorAll(
    "input[required], select[required], textarea[required]"
  );
  let isValid = true;

  inputs.forEach((input) => {
    if (!input.value.trim()) {
      input.style.borderColor = "#ef4444";
      isValid = false;
    } else {
      input.style.borderColor = "#e5e7eb";
    }
  });

  if (!isValid) {
    alert("Mohon lengkapi semua field yang wajib diisi!");
  }

  return isValid;
}

// Sembunyikan alert otomatis
document.addEventListener("DOMContentLoaded", function () {
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    setTimeout(() => {
      alert.style.transition = "opacity 0.5s";
      alert.style.opacity = "0";
      setTimeout(() => {
        alert.remove();
      }, 500);
    }, 5000);
  });
});

// Format Rupiah
function formatRupiah(angka, prefix = "Rp ") {
  const number_string = angka.toString().replace(/[^,\d]/g, "");
  const split = number_string.split(",");
  const sisa = split[0].length % 3;
  let rupiah = split[0].substr(0, sisa);
  const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

  if (ribuan) {
    const separator = sisa ? "." : "";
    rupiah += separator + ribuan.join(".");
  }

  rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
  return prefix + rupiah;
}

// Format Tanggal Indonesia
function formatDateIndo(dateString) {
  const bulan = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];

  const date = new Date(dateString);
  return (
    date.getDate() + " " + bulan[date.getMonth()] + " " + date.getFullYear()
  );
}

// Pratinjau Gambar Sebelum Unggah
function previewImage(input, previewId) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      document.getElementById(previewId).src = e.target.result;
    };

    reader.readAsDataURL(input.files[0]);
  }
}

// Overlay Pemuatan (Loading)
function showLoading() {
  const overlay = document.createElement("div");
  overlay.id = "loadingOverlay";
  overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    `;
  overlay.innerHTML = `
        <div style="background: white; padding: 30px; border-radius: 15px; text-align: center;">
            <i class="fas fa-spinner fa-spin" style="font-size: 3rem; color: #22c55e;"></i>
            <p style="margin-top: 15px; color: #374151;">Memproses...</p>
        </div>
    `;
  document.body.appendChild(overlay);
}

function hideLoading() {
  const overlay = document.getElementById("loadingOverlay");
  if (overlay) {
    overlay.remove();
  }
}

// Fungsi Cetak
function printElement(elementId) {
  const element = document.getElementById(elementId);
  if (!element) return;

  const printWindow = window.open("", "", "height=600,width=800");
  printWindow.document.write("<html><head><title>Cetak</title>");
  printWindow.document.write(
    '<link rel="stylesheet" href="../assets/css/style.css">'
  );
  printWindow.document.write("</head><body>");
  printWindow.document.write(element.innerHTML);
  printWindow.document.write("</body></html>");
  printWindow.document.close();

  setTimeout(() => {
    printWindow.print();
    printWindow.close();
  }, 250);
}

// Ekspor ke CSV
function exportTableToCSV(tableId, filename = "data.csv") {
  const table = document.getElementById(tableId);
  if (!table) return;

  let csv = [];
  const rows = table.querySelectorAll("tr");

  for (let i = 0; i < rows.length; i++) {
    const row = [];
    const cols = rows[i].querySelectorAll("td, th");

    for (let j = 0; j < cols.length; j++) {
      let data = cols[j].innerText
        .replace(/(\r\n|\n|\r)/gm, "")
        .replace(/(\s\s)/gm, " ");
      data = data.replace(/"/g, '""');
      row.push('"' + data + '"');
    }

    csv.push(row.join(","));
  }

  const csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
  const downloadLink = document.createElement("a");
  downloadLink.download = filename;
  downloadLink.href = window.URL.createObjectURL(csvFile);
  downloadLink.style.display = "none";
  document.body.appendChild(downloadLink);
  downloadLink.click();
  document.body.removeChild(downloadLink);
}

// Toggle Sidebar untuk Mobile
function toggleSidebar() {
  const sidebar = document.querySelector(".sidebar");
  if (sidebar) {
    sidebar.classList.toggle("active");
  }
}

// Pencarian dengan fitur debounce (penundaan eksekusi)
let searchTimeout;
function searchData(inputId, callback, delay = 500) {
  const input = document.getElementById(inputId);
  if (!input) return;

  input.addEventListener("input", function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      callback(this.value);
    }, delay);
  });
}

// Notifikasi Toast
function showToast(message, type = "success") {
  const toast = document.createElement("div");
  toast.className = `toast toast-${type}`;
  toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background: ${type === "success" ? "#22c55e" : "#ef4444"};
        color: white;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
  toast.innerHTML = `<i class="fas fa-${
    type === "success" ? "check" : "times"
  }-circle"></i> ${message}`;

  document.body.appendChild(toast);

  setTimeout(() => {
    toast.style.animation = "slideOut 0.3s ease";
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

// Tambahkan keyframes untuk animasi
const style = document.createElement("style");
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Pengecek Kekuatan Kata Sandi
function checkPasswordStrength(password) {
  let strength = 0;

  if (password.length >= 6) strength++;
  if (password.length >= 10) strength++;
  if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
  if (/\d/.test(password)) strength++;
  if (/[^a-zA-Z\d]/.test(password)) strength++;

  const strengths = ["Sangat Lemah", "Lemah", "Sedang", "Kuat", "Sangat Kuat"];
  const colors = ["#ef4444", "#f59e0b", "#eab308", "#22c55e", "#16a34a"];

  return {
    score: strength,
    text: strengths[strength - 1] || strengths[0],
    color: colors[strength - 1] || colors[0],
  };
}

// Inisialisasi saat halaman dimuat
document.addEventListener("DOMContentLoaded", function () {
  console.log("Sistem Pesantren Asshiddiqiyah - Siap!");

  // Tambahkan tombol toggle menu mobile jika diperlukan
  if (window.innerWidth <= 768) {
    const topBar = document.querySelector(".top-bar");
    if (topBar) {
      const menuBtn = document.createElement("button");
      menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
      menuBtn.style.cssText =
        "background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--dark-green);";
      menuBtn.onclick = toggleSidebar;
      topBar.insertBefore(menuBtn, topBar.firstChild);
    }
  }
});
