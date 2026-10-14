document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("click", function (e) {
        const btnEdit = e.target.closest(".btn-edit-kelas");
        if (!btnEdit) return;

        document.getElementById("edit_id_kelas").value = btnEdit.dataset.id;
        document.getElementById("edit_tingkat").value = btnEdit.dataset.tingkat;
        document.getElementById("edit_id_jurusan").value = btnEdit.dataset.jurusan;
        document.getElementById("edit_rombel").value = btnEdit.dataset.rombel;
    });
});
