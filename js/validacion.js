document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

     console.log("✅ Formulario detectado correctamente.");

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        let formData = new FormData(form);

        console.log("📨 Enviando datos del formulario:", Object.fromEntries(formData.entries()));

        fetch("send-email.php", { // Confirma que esta ruta es correcta
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("🔍 Respuesta del servidor:", data); // Depuración
            if (data.success) {
                alert("✅ " + data.message);
                form.reset();
                window.location.href = "exito.html"; // 🔹 Redirige a una página de éxito
            } else {
                alert("❌ " + data.error);
            }
        })
        .catch(error => {
            alert("❌ Hubo un problema al enviar el formulario.");
            console.error("Error:", error);
        });
    });
});
