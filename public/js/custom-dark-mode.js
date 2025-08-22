/**
 * Lógica para el interruptor de modo oscuro en el sidebar.
 * 1. Comprueba la configuración guardada en localStorage al cargar la página.
 * 2. Aplica el modo oscuro si estaba activado.
 * 3. Añade un listener al botón para alternar el modo y guardar la preferencia.
 */
document.addEventListener('DOMContentLoaded', function () {

    // Elementos del DOM
    const toggler = document.getElementById('dark-mode-toggler');
    const body = document.body;

    // Clave para guardar en el almacenamiento local del navegador
    const storageKey = 'darkModeEnabled';

    // Función para aplicar o quitar la clase 'dark-mode' del body
    const applyMode = (isDarkMode) => {
        if (isDarkMode) {
            body.classList.add('dark-mode');
        } else {
            body.classList.remove('dark-mode');
        }
    };

    // Al cargar la página, comprobar si el modo oscuro estaba guardado
    const isDarkModeSaved = localStorage.getItem(storageKey) === 'true';
    applyMode(isDarkModeSaved);

    // Añadir el evento de clic al interruptor
    if (toggler) {
        toggler.addEventListener('click', function (e) {
            // Prevenir la acción por defecto del enlace '#'
            e.preventDefault();

            // Alternar la clase 'dark-mode' en el body
            const isDarkModeNow = body.classList.toggle('dark-mode');

            // Guardar el nuevo estado en localStorage
            localStorage.setItem(storageKey, isDarkModeNow);
        });
    }

});
