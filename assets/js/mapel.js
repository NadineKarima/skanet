document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("click", function (e) {
        const btnEdit = e.target.closest(".btn-edit-mapel");
        if (!btnEdit) return;

        document.getElementById("edit_id_mapel").value = btnEdit.dataset.id;
        document.getElementById("edit_kode_mapel").value = btnEdit.dataset.kode;
        document.getElementById("edit_nama_mapel").value = btnEdit.dataset.nama;
        document.getElementById("edit_kkm").value = btnEdit.dataset.kkm;
        document.getElementById("edit_status").value = btnEdit.dataset.status;
    });
});
