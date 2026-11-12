
document.addEventListener("DOMContentLoaded", function () {

    function validarFormulario(idFormulario) {
        const formulario = document.getElementById(idFormulario);

        if (!formulario) {
            return;
        }

        formulario.addEventListener("submit", function (evento) {
            const campos = formulario.querySelectorAll(
                "input[required], select[required], textarea[required]"
            );

            for (const campo of campos) {
                if (campo.value.trim() === "") {
                    evento.preventDefault();
                    alert("Por favor, completa todos los campos obligatorios.");
                    campo.focus();
                    return;
                }
            }

            if (idFormulario === "formUsuario") {
                const correo = formulario.querySelector(
                    'input[name="correo"]'
                );

                if (!correo.checkValidity()) {
                    evento.preventDefault();
                    alert("Escribe un correo electrónico válido.");
                    correo.focus();
                    return;
                }
            }

            if (idFormulario === "formSeguimiento") {
                const confirmar = confirm(
                    "¿Deseas guardar los cambios del seguimiento?"
                );

                if (!confirmar) {
                    evento.preventDefault();
                }
            }
        });
    }

    validarFormulario("formUsuario");
    validarFormulario("formEquipo");
    validarFormulario("formSolicitud");
    validarFormulario("formSeguimiento");

});
