// Login page functionality
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

if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('emailInput').value.trim();
        const password = passwordInput.value;
        
        if (!email || !password) {
            errorBox.style.display = 'block';
            return;
        }
        
        const formData = new URLSearchParams();
        formData.append('email', email);
        formData.append('password', password);
        
        fetch('login_process.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                errorBox.style.display = 'block';
                passwordInput.value = '';
                passwordInput.focus();
            }
        })
        .catch(() => {
            errorBox.style.display = 'block';
            passwordInput.value = '';
        });
    });
}