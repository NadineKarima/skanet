document.addEventListener("DOMContentLoaded", function () {

    // Delegasi event di document, BUKAN di masing-masing tombol.
    //
    // Kenapa: tabel ini dibuat pakai DataTables dengan opsi
    // responsive:true (lihat datatable.js). Begitu layar sempit dan
    // kolom "Aksi" gak muat, DataTables Responsive akan me-render
    // ULANG isi kolom itu ke dalam "child row" tersembunyi --
    // tombolnya jadi elemen DOM BARU (hasil re-render HTML dari
    // markup aslinya), BUKAN elemen yang sama dengan yang ada pas
    // halaman pertama kali dimuat. class dan data-* atributnya sama
    // persis, tapi listener yang dulu dipasang langsung ke tombol
    // (querySelectorAll(...).forEach(btn => btn.addEventListener(...)))
    // gak ikut nempel ke tombol hasil render ulang itu.
    //
    // Efeknya: klik tombol Edit/Detail di tombol hasil render ulang
    // itu TETAP membuka modal (buka-tutup modal ditangani otomatis
    // oleh Bootstrap lewat atribut data-bs-toggle, bukan lewat kode
    // di file ini), tapi form di dalamnya kosong / gak keisi data
    // sama sekali -- kelihatannya kayak "modal kebuka tapi gak bisa
    // diubah/diinput".
    //
    // Pakai delegasi (listener dipasang sekali di document, terus
    // dicek pakai e.target.closest(...)) supaya ini tetap jalan mau
    // berapa kali pun / di mana pun tombolnya dirender ulang oleh
    // DataTables (ganti halaman, cari data, atau di-collapse jadi
    // child row).

    document.addEventListener("click", function (e) {

        const btnEdit = e.target.closest(".btn-edit-siswa");

        if (btnEdit) {

            document.getElementById("edit_id_siswa").value      = btnEdit.dataset.id;
            document.getElementById("edit_nis").value           = btnEdit.dataset.nis;
            document.getElementById("edit_nama_lengkap").value  = btnEdit.dataset.nama;
            document.getElementById("edit_jenis_kelamin").value = btnEdit.dataset.jk;
            document.getElementById("edit_id_kelas").value      = btnEdit.dataset.kelas;
            document.getElementById("edit_alamat").value        = btnEdit.dataset.alamat;
            document.getElementById("edit_status").value        = btnEdit.dataset.status;

            return;

        }

        const btnDetail = e.target.closest(".btn-detail-siswa");

        if (btnDetail) {

            document.getElementById("detail_nis").textContent    = btnDetail.dataset.nis;
            document.getElementById("detail_nama").textContent   = btnDetail.dataset.nama;
            document.getElementById("detail_jk").textContent     = btnDetail.dataset.jk;
            document.getElementById("detail_kelas").textContent  = btnDetail.dataset.kelas;
            document.getElementById("detail_alamat").textContent = btnDetail.dataset.alamat;
            document.getElementById("detail_status").textContent = btnDetail.dataset.status;

        }

    });

});
