const passwordInput = document.getElementById('passwordInput');
const toggleBtn = document.getElementById('togglePass');
const eyeIcon = document.getElementById('eyeIcon');
const loginForm = document.getElementById('loginForm');
const errorBox = document.getElementById('errorMessage');

// Show/Hide toggle button based on text input length
if (passwordInput) {
    passwordInput.addEventListener('input', function() {
        if (toggleBtn) {
            toggleBtn.style.display = this.value.length > 0 ? 'flex' : 'none';
        }
    });
}

// Toggle Password Visibility
if (toggleBtn) {
    toggleBtn.addEventListener('click', function() {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        
        if (isPassword) {
            eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        } else {
            eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        }
    });
}

// Form Submission Logic - Submit to PHP
if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('emailInput').value;
        const password = passwordInput.value;
        
        // Submit to login_process.php via fetch
        fetch('login_process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password)
        })
        .then(response => response.text())
        .then(result => {
            // Check if redirected to dashboard or user page
            if (result.includes('dashboard') || result.includes('user')) {
                window.location.href = result.trim();
            } else {
                errorBox.style.display = 'block';
                passwordInput.value = '';
                if (toggleBtn) toggleBtn.style.display = 'none';
                passwordInput.focus();
            }
        })
        .catch(error => {
            errorBox.style.display = 'block';
            passwordInput.value = '';
        });
    });
}