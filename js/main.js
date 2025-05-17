// Selección del elemento 'typewriter'
let app = document.getElementById('typewriter');

// Validación: comprobar si el elemento existe
if (app) {
    // Frases dinámicas para el efecto de escritura
    let phrases = [
        'a metros de 9 de julio!',
        'el lugar perfecto para tu entrenamiento.',
        '¡ven y prueba nuestras clases!'
    ];

    // Inicialización de la librería Typewriter
    let typewriter = new Typewriter(app, {
        loop: true, // Hacer que el ciclo se repita
        delay: 75,  // Velocidad de escritura
    });

    // Iterar sobre las frases y añadirlas al efecto
    phrases.forEach((phrase) => {
        typewriter
            .pauseFor(2500) // Pausa antes de empezar
            .typeString(phrase) // Escribir la frase
            .pauseFor(200) // Pausa al final
            .deleteAll(); // Borrar la frase
    });

    // Iniciar el efecto
    typewriter.start();
} else {
    console.error("Elemento con ID 'typewriter' no encontrado en el DOM.");
}
