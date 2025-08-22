document.addEventListener('DOMContentLoaded', function () {
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const darkModeCheckbox = document.getElementById('dark-mode-checkbox');
    const body = document.body;
    const moonIcon = 'fa-moon';
    const sunIcon = 'fa-sun';
    const textElement = darkModeToggle.querySelector('p');

    // Función para aplicar el tema y actualizar el ícono y texto
    function applyTheme(isDarkMode) {
        if (isDarkMode) {
            body.classList.add('dark-mode');
            darkModeToggle.querySelector('.nav-icon').classList.remove(moonIcon);
            darkModeToggle.querySelector('.nav-icon').classList.add(sunIcon);
            textElement.textContent = 'Modo Claro';
            darkModeCheckbox.checked = true;
        } else {
            body.classList.remove('dark-mode');
            darkModeToggle.querySelector('.nav-icon').classList.remove(sunIcon);
            darkModeToggle.querySelector('.nav-icon').classList.add(moonIcon);
            textElement.textContent = 'Modo Oscuro';
            darkModeCheckbox.checked = false;
        }
    }

    // Comprobar la preferencia guardada al cargar la página
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        applyTheme(true);
    } else {
        applyTheme(false);
    }

    // Manejar el clic en el interruptor (el <a> tag)
    darkModeToggle.addEventListener('click', function (e) {
        e.preventDefault(); // Prevenir el comportamiento por defecto del enlace
        const isDarkMode = !body.classList.contains('dark-mode'); // Obtener el estado futuro
        applyTheme(isDarkMode);

        // Guardar la preferencia en localStorage
        if (isDarkMode) {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }
    });

    // Manejar el cambio en el checkbox
    darkModeCheckbox.addEventListener('change', function () {
        const isDarkMode = this.checked;
        applyTheme(isDarkMode);

        // Guardar la preferencia en localStorage
        if (isDarkMode) {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }
    });
});
