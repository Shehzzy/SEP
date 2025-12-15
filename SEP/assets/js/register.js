document.addEventListener('DOMContentLoaded', function() {
    const roleOptions = document.querySelectorAll('.role-option');
    const parentForm = document.getElementById('parentForm');
    const hospitalForm = document.getElementById('hospitalForm');
    
    // Role selection
    roleOptions.forEach(option => {
        option.addEventListener('click', function() {
            const role = this.dataset.role;
            
            // Hide both forms
            parentForm.style.display = 'none';
            hospitalForm.style.display = 'none';
            
            // Remove active class from all options
            roleOptions.forEach(opt => opt.style.borderColor = '#e0e0e0');
            
            // Add active class to selected option
            this.style.borderColor = '#3498db';
            
            // Show selected form
            if (role === 'parent') {
                parentForm.style.display = 'block';
            } else if (role === 'hospital') {
                hospitalForm.style.display = 'block';
            }
        });
    });
    
    // Form validation
    const parentFormElement = document.getElementById('registerParentForm');
    const hospitalFormElement = document.getElementById('registerHospitalForm');
    
    if (parentFormElement) {
        parentFormElement.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = this.querySelector('input[name="password"]').value;
            const confirmPassword = this.querySelector('input[name="confirm_password"]').value;
            
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }
            
            // In real implementation, submit to PHP backend
            alert('Parent registration would be processed here. Redirecting to login...');
            window.location.href = 'login.php';
        });
    }
    
    if (hospitalFormElement) {
        hospitalFormElement.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // In real implementation, submit to PHP backend
            alert('Hospital registration submitted for admin approval. You will be notified once approved.');
            window.location.href = 'login.php';
        });
    }
});