
/*CAMBIAR MENSAJE*/


const botonBienvenida = document.getElementById("btnBienvenida");

botonBienvenida.addEventListener("click", function(){

    const mensaje = document.getElementById("bienvenida");

    mensaje.textContent = "¡Hola! Gracias por visitar mi portafolio.";

});



/* CAMBIAR COLOR */

const btnColor = document.getElementById("btnColor");

btnColor.addEventListener("click", function(){

    const habilidades = document.querySelectorAll(".lista-habilidades li");

    habilidades.forEach(function(item){
        item.style.backgroundColor = "lightpink";
    });

});


/* CAMBIAR FUENTE */

const btnFuente = document.getElementById("btnFuente");

btnFuente.addEventListener("click", function(){

    const habilidades = document.querySelectorAll(".lista-habilidades li");

    habilidades.forEach(function(item){
        item.style.fontFamily = "Georgia";
    });

});



/* VALIDAR FORMULARIO */


function validarFormulario() {

    let nombre = document.getElementById("nombre").value.trim();
    let correo = document.getElementById("email").value.trim();

    // Si faltan los dos
    if (nombre === "" && correo === "") {
        alert("Por favor, escribe tu nombre y tu correo");
        return false;
    }

    // Si falta el nombre
    if (nombre === "") {
        alert("Por favor, escribe tu nombre");
        return false;
    }

    // Si falta el correo
    if (correo === "") {
        alert("El correo es obligatorio");
        return false;
    }

    // Todo correcto
    alert("¡Formulario enviado correctamente!");
    return true;
}