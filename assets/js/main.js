document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let valid = true;
            const requiredFields = this.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = 'red';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });
    
    // Password confirmation check
    const passwordConfirmForm = document.querySelector('form[action="/register.php"]');
    if (passwordConfirmForm) {
        const password = passwordConfirmForm.querySelector('#password');
        const confirmPassword = passwordConfirmForm.querySelector('#confirm_password');
        
        passwordConfirmForm.addEventListener('submit', function(e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                confirmPassword.style.borderColor = 'red';
                alert('Passwords do not match.');
            }
        });
        
        confirmPassword.addEventListener('input', function() {
            if (password.value !== this.value) {
                this.style.borderColor = 'red';
            } else {
                this.style.borderColor = '';
            }
        });
    }
    
    // Image preview for feedback form
    const imageInput = document.querySelector('#image');
    if (imageInput) {
        const imagePreview = document.createElement('div');
        imagePreview.className = 'image-preview';
        imagePreview.style.display = 'none';
        imageInput.parentNode.appendChild(imagePreview);
        
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '200px';
                    img.style.maxHeight = '200px';
                    
                    imagePreview.innerHTML = '';
                    imagePreview.appendChild(img);
                    imagePreview.style.display = 'block';
                }
                
                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    }
    
    // Mobile menu toggle (for smaller screens)
    const menuToggle = document.createElement('button');
    menuToggle.className = 'menu-toggle';
    menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
    menuToggle.style.display = 'none';
    
    const nav = document.querySelector('nav');
    if (nav) {
        nav.parentNode.insertBefore(menuToggle, nav);
        
        menuToggle.addEventListener('click', function() {
            nav.style.display = nav.style.display === 'none' ? 'block' : 'none';
        });
        
        function checkScreenSize() {
            if (window.innerWidth <= 768) {
                menuToggle.style.display = 'block';
                nav.style.display = 'none';
            } else {
                menuToggle.style.display = 'none';
                nav.style.display = 'block';
            }
        }
        
        window.addEventListener('resize', checkScreenSize);
        checkScreenSize();
    }
});