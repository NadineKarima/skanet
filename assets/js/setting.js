document.addEventListener("DOMContentLoaded", function () {

    // ---- Dark mode toggle ----
    const darkSwitch = document.getElementById("darkModeSwitch");

    if (darkSwitch) {

        darkSwitch.addEventListener("change", function () {

            const aktif = darkSwitch.checked;

            document.body.classList.toggle("dark", aktif);

            fetch("actions/setting/toggle_dark_mode.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "aktif=" + (aktif ? "1" : "0")
            }).catch(function () {
                gagal("Gagal menyimpan preferensi, coba lagi.");
            });

        });

    }

    // ---- Form ganti password ----
    const passLama   = document.getElementById("passwordLama");
    const passBaru   = document.getElementById("passwordBaru");
    const passUlang  = document.getElementById("passwordBaruUlang");
    const hint       = document.getElementById("passwordMatchHint");

    if (passBaru && passUlang) {

        const form = passBaru.closest("form");

        form.addEventListener("submit", function (e) {

            // Password baru & ulangannya harus sama
            if (passBaru.value !== passUlang.value) {

                e.preventDefault();

                hint.textContent = "Password baru tidak sama.";
                hint.classList.add("text-danger");

                passUlang.focus();

                return;

            }

            // Password baru gak boleh persis sama kayak password lama.
            // Ini cuma peringatan CEPAT di sisi user -- pengecekan yang
            // beneran valid tetap di server (actions/setting/ganti_password.php),
            // soalnya di sini password lama belum di-hash/diverifikasi.
            if (passLama && passBaru.value !== "" && passLama.value === passBaru.value) {

                e.preventDefault();

                hint.textContent = "Password baru tidak boleh sama dengan password lama.";
                hint.classList.add("text-danger");

                passBaru.focus();

            }

        });

        passUlang.addEventListener("input", function () {
            hint.textContent = "";
            hint.classList.remove("text-danger");
        });

        passBaru.addEventListener("input", function () {
            hint.textContent = "";
            hint.classList.remove("text-danger");
        });

    }

});
