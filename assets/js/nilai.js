document.addEventListener("DOMContentLoaded", function () {

    const table = document.getElementById("tableNilai");

    if (!table) {
        return;
    }

    const kkm = parseFloat(table.dataset.kkm) || 0;

    // PENTING: rumus predikat di bawah ini HARUS SAMA PERSIS sama
    // includes/nilai_helper.php (hitungPredikat). Ini cuma preview
    // visual -- yang beneran kesimpen tetap dihitung ulang di server.
    function hitungPredikat(nilai) {

        if (nilai >= 90) {
            return "A";
        }

        if (nilai >= 80) {
            return "B";
        }

        if (nilai >= 70) {
            return "C";
        }

        return "D";

    }

    document.querySelectorAll(".input-nilai").forEach(function (input) {

        input.addEventListener("input", function () {

            const row = input.closest("tr");
            const predikatCell = row.querySelector(".preview-predikat");

            const nilai = parseFloat(input.value);

            if (isNaN(nilai) || input.value === "") {

                predikatCell.textContent = "-";
                predikatCell.classList.remove("text-danger", "fw-bold");
                input.classList.remove("text-danger", "fw-bold");

                return;

            }

            predikatCell.textContent = hitungPredikat(nilai);

            const belowKkm = nilai < kkm;

            input.classList.toggle("text-danger", belowKkm);
            input.classList.toggle("fw-bold", belowKkm);
            predikatCell.classList.toggle("text-danger", belowKkm);
            predikatCell.classList.toggle("fw-bold", belowKkm);

        });

    });

});

/**
 * Dipanggil dari tombol "Edit" (ikon pensil) di kolom Aksi.
 * Nilai diedit langsung inline di tabel (bukan modal terpisah),
 * jadi "Edit" di sini artinya "bawa saya ke sini", bukan buka form
 * baru -- scroll ke baris siswa itu dan fokus ke kolom Nilai-nya.
 */
function fokusNilai(idSiswa) {

    const row = document.getElementById("rowSiswa" + idSiswa);

    if (!row) {
        return;
    }

    row.scrollIntoView({ behavior: "smooth", block: "center" });

    const inputNilai = document.getElementById("nilai_" + idSiswa);

    if (inputNilai) {
        inputNilai.focus();
    }

    row.classList.add("table-warning");

    setTimeout(function () {
        row.classList.remove("table-warning");
    }, 1500);

}
