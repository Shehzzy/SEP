document.addEventListener('DOMContentLoaded', function() {
    const passwordToggle = document.querySelector('.password-toggle');
    const accountTypes = document.querySelectorAll('.account-type');
    
    // Password toggle
    if (passwordToggle) {
        passwordToggle.addEventListener('click', function() {
            const passwordInput = document.querySelector('input[name="password"]');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }
    
    // Account type selection
    accountTypes.forEach(type => {
        type.addEventListener('click', function() {
            accountTypes.forEach(t => t.style.backgroundColor = '#f8f9fa');
            this.style.backgroundColor = '#e3f2fd';
            this.style.borderColor = '#3498db';
        });
    });
});