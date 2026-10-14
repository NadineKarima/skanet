document.addEventListener("DOMContentLoaded", function () {

    const canvas = document.getElementById("nilaiChart");

    if (!canvas) return;

    fetch("api/dashboard/chart.php")
        .then(response => response.json())
        .then(data => {

            new Chart(canvas, {

                type: "bar",

                data: {

                    labels: [

                        "Guru",
                        "Siswa",
                        "Jurusan",
                        "Kelas",
                        "Mapel",
                        "Nilai"

                    ],

                    datasets: [{

                        label: "Jumlah Data",

                        data: [

                            data.guru,
                            data.siswa,
                            data.jurusan,
                            data.kelas,
                            data.mapel,
                            data.nilai

                        ],

                        borderRadius: 10

                    }]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {

                        legend: {

                            display: false

                        }

                    },

                    scales: {

                        y: {

                            beginAtZero: true

                        }

                    }

                }

            });

        });

});