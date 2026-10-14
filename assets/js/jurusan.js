document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("click", function (e) {
        const btnEdit = e.target.closest(".btn-edit-jurusan");
        if (!btnEdit) return;

        document.getElementById("edit_id_jurusan").value = btnEdit.dataset.id;
        document.getElementById("edit_nama_jurusan").value = btnEdit.dataset.nama;
    });
});
