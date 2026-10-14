document.addEventListener("DOMContentLoaded", function () {

    const sidebar = document.getElementById("sidebar");
    const main    = document.querySelector(".main-content");
    const toggle  = document.getElementById("toggleSidebar");
    const overlay = document.getElementById("sidebarOverlay");

    function bukaSidebarMobile() {
        if (sidebar) sidebar.classList.add("show");
        if (overlay) overlay.classList.add("show");
    }

    function tutupSidebarMobile() {
        if (sidebar) sidebar.classList.remove("show");
        if (overlay) overlay.classList.remove("show");
    }

    if (toggle) {
        toggle.addEventListener("click", function () {
            if (window.innerWidth <= 992) {
                if (sidebar && sidebar.classList.contains("show")) {
                    tutupSidebarMobile();
                } else {
                    bukaSidebarMobile();
                }
            } else {
                if (sidebar) sidebar.classList.toggle("hide");
                if (main) main.classList.toggle("full");
            }
        });
    }

    if (overlay) {
        overlay.addEventListener("click", tutupSidebarMobile);
    }

    document.querySelectorAll(".sidebar a").forEach(function (link) {
        link.addEventListener("click", function () {
            if (window.innerWidth <= 992) {
                tutupSidebarMobile();
            }
        });
    });

    function updateTime() {
        const el = document.getElementById("dateTime");
        if (!el) return;

        const now = new Date();
        el.innerHTML = now.toLocaleString("id-ID");
    }

    updateTime();
    setInterval(updateTime, 1000);

    /*
     * Bootstrap modal/offcanvas harus berada langsung di <body>.
     * Sebelumnya komponen-komponen ini berada di dalam .content.
     * .content mempunyai animation/stacking context sehingga backdrop
     * dapat berada di atas modal dan membuat tombol/input tidak bisa
     * menerima klik. Memindahkannya ke body membuat z-index Bootstrap
     * bekerja normal: modal bisa diklik, diisi, ditutup, dan Escape/
     * klik backdrop kembali berfungsi.
     */
    document.querySelectorAll(".modal, .offcanvas").forEach(function (element) {
        if (element.parentElement !== document.body) {
            document.body.appendChild(element);
        }
    });

});
