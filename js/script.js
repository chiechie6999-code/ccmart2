document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        // Age calculation
        const birthdateInput = document.getElementById('birthdate');
        const ageInput = document.getElementById('age');
        birthdateInput.addEventListener('change', function() {
            const birthdate = new Date(this.value);
            if (!isNaN(birthdate)) {
                const today = new Date();
                let age = today.getFullYear() - birthdate.getFullYear();
                const m = today.getMonth() - birthdate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
                    age--;
                }
                ageInput.value = age;
                validateAge(age);
            } else {
                ageInput.value = '';
            }
        });

        const familyNameInput = document.getElementById('family_name');
        if (familyNameInput) {
            familyNameInput.addEventListener('input', function() {
                const familyNameError = document.getElementById('family_name_error');
                const familyVal = this.value.trim();
                if (familyVal && /^[a-z]/.test(familyVal)) {
                    familyNameError.textContent = 'First Letter of your family name must start with capital';
                } else {
                    familyNameError.textContent = '';
                }
            });
        }

        // Live validation helpers
        function attachValidation(inputId, errorId, validateFn) {
            const input = document.getElementById(inputId);
            const err = document.getElementById(errorId);
            if (!input) return;
            const handler = () => {
                const res = validateFn(input.value.trim());
                if (res === true) {
                    setValid(input, err);
                } else if (typeof res === 'string') {
                    setInvalid(input, err, res);
                }
            };
            input.addEventListener('input', handler);
            if (input.type === 'date') input.addEventListener('change', handler);
        }

        // Client-side validation for registration form
        registerForm.addEventListener('submit', function(e) {
            let isValid = true;

            // Clear previous errors
            document.querySelectorAll('.error').forEach(el => el.textContent = '');

            // Validate all fields
            const fields = [
                { id: 'id_number', errorId: 'id_number_error', validate: v => /^\d{4}-\d{4}$/.test(v) ? true : 'ID Number must be in the format xxxx-xxxx.' },
                { id: 'email', errorId: 'email_error', validate: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) ? true : 'Invalid email format.' },
                {
                    id: 'username',
                    errorId: 'username_error',
                    validate: v => {
                        if (!v) return 'Username is required.';
                        if (v.length < 3) return 'Username must be at least 3 characters.';
                        if (v.length > 50) return 'Username cannot exceed 50 characters.';
                        if (!/^[a-zA-Z0-9_]+$/.test(v)) return 'Username can only contain letters, numbers, and underscores.';
                        return true;
                    }
                },
                {
                    id: 'first_name',
                    errorId: 'first_name_error',
                    validate: v => {
                        const result = validateNameField(v, 'First Name');
                        return result.isValid ? true : result.message;
                    }
                },
                {
                    id: 'middle_name',
                    errorId: 'middle_name_error',
                    validate: v => {
                        if (!v) return true; // Optional field
                        const result = validateNameField(v, 'Middle Name');
                        return result.isValid ? true : result.message;
                    }
                },
                {
                    id: 'family_name',
                    errorId: 'family_name_error',
                    validate: v => {
                        const result = validateNameField(v, 'Family Name');
                        return result.isValid ? true : result.message;
                    }
                },
                {
                    id: 'name_extension',
                    errorId: 'name_extension_error',
                    validate: v => {
                        if (!v) return true; // Optional field
                        if (!/^[A-Za-z\s.,]+$/.test(v)) return 'Name Extension contains invalid characters.';
                        if (v.length > 10) return 'Name Extension cannot exceed 10 characters.';
                        return true;
                    }
                },
                { id: 'birthdate', errorId: 'birthdate_error', validate: v => {
                    if (!v) return 'Birthdate is required.';
                    const birthDate = new Date(v);
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    if (age < 18) return 'You must be at least 18 years old to register.';
                    return true;
                }},
                {
                    id: 'purok_street',
                    errorId: 'purok_street_error',
                    validate: v => {
                        if (!v) return 'Purok/Street is required.';
                        if (v.length > 100) return 'Purok/Street cannot exceed 100 characters.';
                        return true;
                    }
                },
                {
                    id: 'barangay',
                    errorId: 'barangay_error',
                    validate: v => {
                        if (!v) return 'Barangay is required.';
                        if (v.length > 50) return 'Barangay cannot exceed 50 characters.';
                        return true;
                    }
                },
                {
                    id: 'municipality_city',
                    errorId: 'municipality_city_error',
                    validate: v => {
                        if (!v) return 'Municipality/City is required.';
                        if (v.length > 50) return 'Municipality/City cannot exceed 50 characters.';
                        return true;
                    }
                },
                {
                    id: 'province',
                    errorId: 'province_error',
                    validate: v => {
                        if (!v) return 'Province is required.';
                        if (v.length > 50) return 'Province cannot exceed 50 characters.';
                        return true;
                    }
                },
                {
                    id: 'country',
                    errorId: 'country_error',
                    validate: v => {
                        if (!v) return 'Country is required.';
                        if (v.length > 50) return 'Country cannot exceed 50 characters.';
                        return true;
                    }
                },
                {
                    id: 'zip_code',
                    errorId: 'zip_code_error',
                    validate: v => {
                        if (!v) return 'ZIP code is required.';
                        if (!/^\d{4}$/.test(v)) return 'ZIP code must be exactly 4 digits.';
                        return true;
                    }
                },
                { id: 'password', errorId: 'password_error', validate: v => {
                    if (!v) return 'Password is required.';
                    if (v.length < 8) return 'Password must be at least 8 characters.';
                    if (!/[A-Z]/.test(v)) return 'Password must contain at least one uppercase letter.';
                    if (!/[a-z]/.test(v)) return 'Password must contain at least one lowercase letter.';
                    if (!/\d/.test(v)) return 'Password must contain at least one number.';
                    return true;
                }},
                { id: 're_password', errorId: 're_password_error', validate: v => {
                    if (!v) return 'Please confirm your password.';
                    if (v !== document.getElementById('password').value) return 'Passwords do not match.';
                    return true;
                }},
                { id: 'answer1', errorId: 'answer1_error', validate: v => v ? true : 'Answer is required.' },
                { id: 'answer2', errorId: 'answer2_error', validate: v => v ? true : 'Answer is required.' },
                { id: 'answer3', errorId: 'answer3_error', validate: v => v ? true : 'Answer is required.' }
            ];

            fields.forEach(field => {
                const input = document.getElementById(field.id);
                const errorEl = document.getElementById(field.errorId);
                if (input && errorEl) {
                    const result = field.validate(input.value.trim());
                    if (result !== true) {
                        errorEl.textContent = result;
                        isValid = false;
                        // Scroll to the first error
                        if (isValid === false) {
                            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            input.focus();
                            isValid = null; // Prevent further scrolling
                        }
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Username availability check
        const usernameInput = document.getElementById('username');
        const usernameError = document.getElementById('username_error');
        usernameInput.addEventListener('blur', function() {
            const username = this.value;
            if (username.length > 0) {
                fetch('/ccmart/php/check_username.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'username=' + encodeURIComponent(username)
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'taken') {
                        setInvalid(usernameInput, usernameError, 'Username is already taken.');
                    } else if (!/^[A-Za-z0-9_]{4,50}$/.test(username)) {
                        setInvalid(usernameInput, usernameError, 'Username must be 4-50 characters, letters/numbers/underscore only.');
                    } else {
                        setValid(usernameInput, usernameError);
                    }
                });
            }
        });

        // Password strength meter
        const passwordInput = document.getElementById('password');
        const passwordStrengthDiv = document.getElementById('password-strength');
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            let strengthText = 'Weak';
            let color = 'red';
            if (strength >= 5) {
                strengthText = 'Strong';
                color = 'green';
            } else if (strength >= 3) {
                strengthText = 'Medium';
                color = 'orange';
            }
            passwordStrengthDiv.textContent = 'Strength: ' + strengthText;
            passwordStrengthDiv.style.color = color;
        });
    }

    // Generic show/hide password toggles
    document.querySelectorAll('.toggle-password').forEach(btn => {
        const icon = btn.querySelector('i');
        // Set initial state
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');

        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    });

    // Login page logic
    const loginContainer = document.querySelector('.container[data-login-attempts]');
    if (loginContainer) {
        const loginForm = document.getElementById('login-form');
        const passwordInput = document.getElementById('password');
        const forgotPasswordContainer = document.getElementById('forgot-password-container');
        const loginButton = document.getElementById('login-button');
        const registerLink = document.getElementById('register-link');
        const errorMessageDiv = document.getElementById('login-error-message');

        // Disable browser back button on login page
        (function disableBackButton(){
            history.pushState(null, '', location.href);
            window.onpopstate = function () {
                history.pushState(null, '', location.href);
            };
        })();

        // Client-side validation for login
        if (loginForm) {
            loginForm.addEventListener('submit', function(e){
                const userEl = document.getElementById('username');
                const userVal = (userEl && userEl.value ? userEl.value.trim() : '');
                const passVal = (passwordInput && passwordInput.value ? passwordInput.value : '');
                const usernameOk = /^[A-Za-z0-9_]{4,50}$/.test(userVal);
                if (!usernameOk || !passVal) {
                    e.preventDefault();
                    errorMessageDiv.textContent = !usernameOk ? 'Invalid username. Use 4-50 letters, numbers, or underscore.' : 'Password is required.';
                }
            });
        }

        // Show "Forgot Password?" link
        const loginAttempts = parseInt(loginContainer.dataset.loginAttempts, 10);
        if (loginAttempts >= 2) {
            forgotPasswordContainer.style.display = 'block';
        }

        // Handle lockout timer
        const lockoutTime = parseInt(loginContainer.dataset.lockoutTime, 10);
        const currentTime = parseInt(loginContainer.dataset.currentTime, 10);

        if (lockoutTime && currentTime && lockoutTime > currentTime) {
            let remainingTime = lockoutTime - currentTime;

            // Disable form elements
            loginButton.disabled = true;
            registerLink.style.pointerEvents = 'none';
            registerLink.style.color = 'grey';

            const timerInterval = setInterval(() => {
                if (remainingTime > 0) {
                    errorMessageDiv.textContent = `Too many failed login attempts. Please try again in ${remainingTime} seconds.`;
                    remainingTime--;
                } else {
                    clearInterval(timerInterval);
                    errorMessageDiv.textContent = 'You can now try to log in again.';
                    loginButton.disabled = false;
                    registerLink.style.pointerEvents = 'auto';
                    registerLink.style.color = ''; // Revert to default color
                }
            }, 1000);
        }
    }
});

function validateName(name, fieldName = 'Name') {
    if (!name) return { isValid: true }; // for optional fields

    if (name.length < 2) {
        return { isValid: false, message: `${fieldName} must be at least 2 characters long.` };
    }
    if (name.length > 50) {
        return { isValid: false, message: `${fieldName} cannot exceed 50 characters.` };
    }
    if (/\d/.test(name)) {
        return { isValid: false, message: `${fieldName} cannot contain numbers.` };
    }
    if (!/^[a-zA-Z\s'-]+$/.test(name)) {
        return { isValid: false, message: `${fieldName} contains invalid characters.`};
    }
    if (/\s{2,}/.test(name)) {
        return { isValid: false, message: `${fieldName} should not contain multiple spaces.` };
    }
    if (name.length > 1 && name === name.toUpperCase()) {
        return { isValid: false, message: `${fieldName} should not be all uppercase.` };
    }
    if (/([a-zA-Z])\1{2,}/i.test(name)) {
        return { isValid: false, message: `${fieldName} contains three or more repeated letters.` };
    }
    const words = name.split(/\s+/);
    for (const word of words) {
        if (word.length > 0 && !/^[A-Z][a-z]*$/.test(word)) {
            return { isValid: false, message: `Each word in ${fieldName} must start with an uppercase letter.` };
        }
    }

    return { isValid: true };
}

function validateAge(age) {
    const birthdateError = document.getElementById('birthdate_error');
    if (isNaN(age) || age < 18) {
        birthdateError.textContent = 'You must be at least 18 years old to register.';
        return false;
    } else {
        birthdateError.textContent = '';
        return true;
    }
}

// Helper function to validate name fields with custom fieldName
function validateNameField(value, fieldName) {
    if (!value) {
        return { isValid: false, message: `${fieldName} is required.` };
    }

    // Trim and check length
    const trimmed = value.trim();
    if (trimmed.length < 2) {
        return { isValid: false, message: `${fieldName} must be at least 2 characters.` };
    }

    if (trimmed.length > 30) {
        return { isValid: false, message: `${fieldName} cannot exceed 30 characters.` };
    }

    // Check for numbers
    if (/\d/.test(trimmed)) {
        return { isValid: false, message: `${fieldName} cannot contain numbers.` };
    }

    // Check for invalid characters (allow letters, spaces, and hyphens)
    if (!/^[a-zA-Z\s-]+$/.test(trimmed)) {
        return { isValid: false, message: `${fieldName} can only contain letters, spaces, and hyphens.` };
    }

    // Check for double spaces or invalid hyphen usage
    if (/\s{2,}|-{2,}|^-|-$|\s-|-\s/.test(trimmed)) {
        return {
            isValid: false,
            message: `${fieldName} has invalid spacing or hyphen usage.`
        };
    }

    // Check for three or more repeated letters in a row
    if (/([a-zA-Z])\1{2,}/.test(trimmed)) {
        return {
            isValid: false,
            message: `${fieldName} cannot contain three or more repeated letters in a row.`
        };
    }

    // Check for proper capitalization (Firstname Lastname or Lastname-Othername)
    const words = trimmed.split(/[\s-]+/);
    for (const word of words) {
        if (!/^[A-Z][a-z]*$/.test(word)) {
            return {
                isValid: false,
                message: `Each part of ${fieldName} must start with an uppercase letter followed by lowercase letters.`
            };
        }
    }

    return { isValid: true };
}

// Visual state helpers: invalid (red) and valid (green)
function setInvalid(inputEl, errorEl, message) {
    if (inputEl) {
        inputEl.classList.remove('valid');
    }
    if (errorEl) {
        errorEl.classList.remove('success');
        errorEl.textContent = message || '';
    }
}

function setValid(inputEl, errorEl) {
    if (inputEl) {
        inputEl.classList.add('valid');
    }
    if (errorEl) {
        errorEl.classList.add('success');
        // Clear helper text instead of showing 'Valid'
        errorEl.textContent = '';
    }
}
