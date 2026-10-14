function sukses(pesan){

    Swal.fire({

        icon:"success",

        title:"Berhasil",

        text:pesan,

        timer:1800,

        showConfirmButton:false

    });

}

function gagal(pesan){

    Swal.fire({

        icon:"error",

        title:"Oops...",

        text:pesan

    });

}

function konfirmasi(url){

    Swal.fire({

        title:"Yakin?",

        text:"Data akan dihapus.",

        icon:"warning",

        showCancelButton:true,

        confirmButtonText:"Ya",

        cancelButtonText:"Batal"

    }).then((result)=>{

        if(result.isConfirmed){

            window.location=url;

        }

    });

}