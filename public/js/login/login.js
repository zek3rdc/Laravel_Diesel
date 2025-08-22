document.addEventListener('DOMContentLoaded', function() {
    // Animate panels on page load
    document.body.classList.add('loaded');

    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    const loginBtnText = document.getElementById('loginBtnText');
    const passwordField = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    // Password visibility toggle
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }

    // Form submission handler
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            // Basic client-side validation check
            if (!this.checkValidity()) {
                e.preventDefault();
                // Add shake animation for invalid form
                this.closest('.login-card').classList.add('animate__animated', 'animate__shakeX');
                setTimeout(() => {
                    this.closest('.login-card').classList.remove('animate__animated', 'animate__shakeX');
                }, 1000);
                return;
            }

            // Show loading state
            loginBtn.disabled = true;
            loginBtnText.innerHTML = '<span class="loading-spinner"></span>Iniciando sesión...';
        });
    }
});