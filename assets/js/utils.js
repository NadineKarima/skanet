function previewPassword(id){

    const input=document.getElementById(id);

    if(input.type==="password"){

        input.type="text";

    }else{

        input.type="password";

    }

}

function resetForm(id){

    document.getElementById(id).reset();

}