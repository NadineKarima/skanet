document.addEventListener("DOMContentLoaded", function () {

    const formTambah = document.getElementById("formTambahGuru");

    if (formTambah) {
        const pass1 = document.getElementById("passwordGuru");
        const pass2 = document.getElementById("password2");
        const hint  = document.getElementById("passwordMatchHint");

        formTambah.addEventListener("submit", function (e) {
            if (pass1.value !== pass2.value) {
                e.preventDefault();
                hint.textContent = "Password tidak sama.";
                hint.classList.add("text-danger");
                pass2.focus();
            }
        });

        pass2.addEventListener("input", function () {
            hint.textContent = "";
            hint.classList.remove("text-danger");
        });
    }

    document.addEventListener("click", function (e) {
        const btnDetail = e.target.closest(".btn-detail-guru");

        if (btnDetail) {
            document.getElementById("detailGuru_nip").textContent      = btnDetail.dataset.nip;
            document.getElementById("detailGuru_username").textContent = btnDetail.dataset.username;
            document.getElementById("detailGuru_nama").textContent     = btnDetail.dataset.nama;
            document.getElementById("detailGuru_jk").textContent       = btnDetail.dataset.jk;
            document.getElementById("detailGuru_hp").textContent       = btnDetail.dataset.hp;
            document.getElementById("detailGuru_alamat").textContent   = btnDetail.dataset.alamat;
            document.getElementById("detailGuru_mapel").textContent    = btnDetail.dataset.mapel;
            document.getElementById("detailGuru_status").textContent   = btnDetail.dataset.status;
            return;
        }

        const btnEdit = e.target.closest(".btn-edit-guru");

        if (btnEdit) {
            const id = btnEdit.dataset.id;

            document.getElementById("edit_id_guru").value = id;
            document.getElementById("edit_nip_guru").value = btnEdit.dataset.nip;
            document.getElementById("edit_username_guru").value = btnEdit.dataset.username;
            document.getElementById("edit_nama_guru").value = btnEdit.dataset.nama;
            document.getElementById("edit_jenis_kelamin_guru").value = btnEdit.dataset.jkValue;
            document.getElementById("edit_no_hp_guru").value = btnEdit.dataset.hp;
            document.getElementById("edit_alamat_guru").value = btnEdit.dataset.alamat;
            document.getElementById("edit_status_guru").value = btnEdit.dataset.status;

            const assigned = (btnEdit.dataset.mapelIds || "")
                .split(",")
                .map(value => value.trim())
                .filter(Boolean);

            document.querySelectorAll(".edit-guru-mapel").forEach(function (checkbox) {
                checkbox.checked = assigned.includes(checkbox.value);
            });

            const resetButton = document.getElementById("btnResetPasswordGuru");
            if (resetButton) {
                resetButton.setAttribute(
                    "onclick",
                    "konfirmasi('actions/guru/reset_password.php?id=" + encodeURIComponent(id) + "')"
                );
            }
        }
    });

});
