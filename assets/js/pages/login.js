const passwordInput = document.getElementById('passwordInput');
const toggleBtn = document.getElementById('togglePass');
const eyeIcon = document.getElementById('eyeIcon');
const loginForm = document.getElementById('loginForm');
const errorBox = document.getElementById('errorMessage');

if (passwordInput) {
    passwordInput.addEventListener('input', function() {
        if (toggleBtn) {
            toggleBtn.style.display = this.value.length > 0 ? 'flex' : 'none';
        }
    });
}

if (toggleBtn) {
    toggleBtn.addEventListener('click', function() {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        
        if (isPassword) {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });
}
