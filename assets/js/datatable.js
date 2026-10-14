document.addEventListener("DOMContentLoaded", function () {

    const options = {

        pageLength: 10,

        responsive: true,

        // Kolom terakhir di semua tabel di sini isinya tombol Aksi
        // (Edit/Detail/Hapus). responsivePriority: 1 bikin kolom itu
        // paling terakhir yang di-collapse DataTables Responsive kalau
        // layar sempit -- idealnya malah gak pernah di-collapse sama
        // sekali selama kolom lain masih bisa "mengalah" duluan.
        //
        // Ini PENTING, bukan cuma soal tampilan: begitu kolom Aksi
        // di-collapse, DataTables Responsive me-render ULANG tombol di
        // kolom itu ke dalam "child row" sebagai elemen DOM BARU. Elemen
        // baru itu gak kebawa event listener yang dipasang lewat
        // querySelectorAll(...).forEach(...) di siswa.js/guru.js/dst
        // waktu halaman pertama kali dimuat -- walaupun class dan
        // data-*-nya sama persis. Efeknya tombol Edit/Detail kelihatan
        // ada dan modalnya kebuka, tapi form di dalamnya kosong / gak
        // keisi data (kelihatan kayak "kebuka tapi gak bisa diinput").
        //
        // Kombinasi kolom Aksi gak pernah collapse (fix di sini) +
        // listener dipasang lewat delegasi di document (fix di
        // siswa.js/guru.js/jurusan.js/kelas.js/mapel.js) bikin
        // tombol Edit/Detail tetap jalan di ukuran layar berapa pun.
        columnDefs: [
            { responsivePriority: 1, targets: -1 }
        ],

        language: {

            search: "Cari :",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",

            zeroRecords: "Data tidak ditemukan",

            paginate: {

                first: "Awal",

                last: "Akhir",

                next: "\u203a",

                previous: "\u2039"

            }

        }

    };

    const tables = [

        "tableJurusan",

        "tableGuru",

        "tableSiswa",

        "tableMapel",

        "tableNilai",

        "tableKelas",

        "tableLaporan"

    ];

    tables.forEach(function (id) {

        if (document.getElementById(id)) {

            new DataTable("#" + id, options);

        }

    });

});
