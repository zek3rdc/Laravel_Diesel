(function() {
  "use strict";

  const theme = {
    init: function() {
      this.applySavedTheme();
      this.attachEventListeners();
    },

    applySavedTheme: function() {
      const darkMode = localStorage.getItem('darkMode');
      const sidebarColor = localStorage.getItem('sidebarColor');
      const sidebarType = localStorage.getItem('sidebarType');
      const navbarFixed = localStorage.getItem('navbarFixed');

      if (darkMode === 'true') {
        const darkVersionSwitch = document.getElementById('dark-version');
        if (darkVersionSwitch) {
          darkVersionSwitch.checked = true;
          window.darkMode(darkVersionSwitch);
        }
        document.getElementById('actionModal').classList.add('dark-mode');
      } else {
        document.getElementById('actionModal').classList.remove('dark-mode');
      }

      if (sidebarColor) {
        const colorButton = document.querySelector(`.badge[data-color="${sidebarColor}"]`);
        if (colorButton) {
          window.sidebarColor(colorButton);
        }
      }

      if (sidebarType) {
        const typeButton = document.querySelector(`.btn[data-class="${sidebarType}"]`);
        if (typeButton) {
            window.sidebarType(typeButton);
        }
      }

      if (navbarFixed === 'true') {
        const navbarFixedSwitch = document.getElementById('navbarFixed');
        if (navbarFixedSwitch) {
          navbarFixedSwitch.checked = true;
          window.navbarFixed(navbarFixedSwitch);
        }
      }
    },

    attachEventListeners: function() {
      // Dark mode
      const darkVersionSwitch = document.getElementById('dark-version');
      if (darkVersionSwitch) {
        darkVersionSwitch.addEventListener('click', function() {
          localStorage.setItem('darkMode', this.checked);
          if (this.checked) {
            document.getElementById('actionModal').classList.add('dark-mode');
          } else {
            document.getElementById('actionModal').classList.remove('dark-mode');
          }
        });
      }

      // Sidebar color
      const colorButtons = document.querySelectorAll('.switch-trigger .badge');
      colorButtons.forEach(button => {
        button.addEventListener('click', function() {
          localStorage.setItem('sidebarColor', this.getAttribute('data-color'));
        });
      });

      // Sidenav type
      const typeButtons = document.querySelectorAll('.d-flex .btn[data-class]');
      typeButtons.forEach(button => {
          button.addEventListener('click', function() {
              localStorage.setItem('sidebarType', this.getAttribute('data-class'));
          });
      });

      // Navbar fixed
      const navbarFixedSwitch = document.getElementById('navbarFixed');
      if (navbarFixedSwitch) {
        navbarFixedSwitch.addEventListener('click', function() {
          localStorage.setItem('navbarFixed', this.checked);
        });
      }
    }
  };

  document.addEventListener('DOMContentLoaded', function() {
    theme.init();
  });

  // Asegurarse de que el tema se aplique en navegaciones de caché (atrás/adelante)
  window.addEventListener('pageshow', function(event) {
    // event.persisted es true si la página se carga desde la caché
    if (event.persisted) {
      theme.applySavedTheme();
    }
  });

})();
