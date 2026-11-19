document.addEventListener("DOMContentLoaded", () => {

    const preguntas = document.querySelectorAll(".tema");

    preguntas.forEach((pregunta) => {

        const opciones = pregunta.querySelectorAll(".opcion");

        opciones.forEach((opcion) => {

            opcion.addEventListener("click", () => {

                // Si ya respondieron la pregunta, no hacer nada
                if (pregunta.classList.contains("respondida")) {
                    return;
                }

                pregunta.classList.add("respondida");

                opciones.forEach((boton) => {

                    boton.disabled = true;

                    if (boton.dataset.correcta === "true") {
                        boton.classList.add("correcta");
                    }

                });

                if (opcion.dataset.correcta === "true") {

                    opcion.classList.add("correcta");

                    setTimeout(() => {
                        alert("✅ ¡Respuesta correcta!");
                    }, 100);

                } else {

                    opcion.classList.add("incorrecta");

                    setTimeout(() => {
                        alert("❌ Respuesta incorrecta");
                    }, 100);

                }

            });

        });

    });

});