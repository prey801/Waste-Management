// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    // Set current year in footer
    document.getElementById('current-year').textContent = new Date().getFullYear();
    
    // Geolocation button handler
    const geolocateBtns = document.querySelectorAll('#geolocateBtn');
    if (geolocateBtns) {
        geolocateBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const locationInput = this.closest('.mb-3').querySelector('#location');
                            if (locationInput) {
                                // In a real app, you would reverse geocode to get an address
                                locationInput.value = `${position.coords.latitude}, ${position.coords.longitude}`;
                                alert('Location captured successfully!');
                            }
                        }.bind(this),
                        function(error) {
                            console.error('Error getting location:', error);
                            alert('Could not get your location. Please enter it manually.');
                        }
                    );
                } else {
                    alert('Geolocation is not supported by your browser.');
                }
            });
        });
    }
    
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const strengthText = document.getElementById('passwordStrength');
            if (strengthText) {
                const strength = calculatePasswordStrength(this.value);
                strengthText.textContent = strength.text;
                strengthText.style.color = strength.color;
            }
        });
    }
    
    // Form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simple form validation
            let isValid = true;
            const inputs = this.querySelectorAll('input[required], select[required], textarea[required]');
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            // Password confirmation check
            const password = this.querySelector('#password');
            const confirmPassword = this.querySelector('#confirmPassword');
            if (password && confirmPassword && password.value !== confirmPassword.value) {
                isValid = false;
                confirmPassword.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = 'Passwords do not match';
                confirmPassword.parentNode.appendChild(errorDiv);
            }
            
            if (isValid) {
                // Simulate form submission
                if (this.id === 'recoveryForm') {
                    document.getElementById('recoveryStep1').classList.add('d-none');
                    document.getElementById('recoveryStep2').classList.remove('d-none');
                } else {
                    alert('Form submitted successfully!');
                    this.reset();
                    
                    // Redirect if it's login form
                    if (this.id === 'loginForm') {
                        window.location.href = 'dashboard.html';
                    }
                    
                    // Redirect if it's signup form
                    if (this.id === 'signupForm') {
                        window.location.href = 'login.html';
                    }
                }
            }
        });
    });
    
    // Password strength calculator
    function calculatePasswordStrength(password) {
        let strength = 0;
        
        // Length check
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        
        // Character variety checks
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        // Return result
        if (strength <= 2) {
            return { text: 'Weak', color: '#dc3545' };
        } else if (strength <= 4) {
            return { text: 'Moderate', color: '#fd7e14' };
        } else {
            return { text: 'Strong', color: '#198754' };
        }
    }
    
    // Animation for feature cards on scroll
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.feature-card, .step');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementPosition < windowHeight - 100) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    };
    
    // Add scroll event listener
    window.addEventListener('scroll', animateOnScroll);
    // Initial check in case elements are already in view
    animateOnScroll();
    
    // Highlight current page in navbar
    const currentPage = location.pathname.split('/').pop() || 'index.html';
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const linkHref = link.getAttribute('href');
        if (linkHref === currentPage) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
    
    // Dashboard chart initialization
    if (document.getElementById('statusChart')) {
        // This is handled by the inline script in dashboard.html
    }
});