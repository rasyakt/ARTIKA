/**
 * Password Toggle Visibility Utility
 * Fungsi universal untuk toggle show/hide password di semua form
 */

function initializePasswordToggles() {
    // Cari semua input password fields
    const passwordFields = document.querySelectorAll('input[type="password"]');
    
    passwordFields.forEach(field => {
        // Jika belum ada toggle button, buat satu
        if (!field.parentElement.querySelector('.toggle-password')) {
            createPasswordToggle(field);
        }
    });
}

/**
 * Membuat toggle button untuk password field
 */
function createPasswordToggle(inputField) {
    // Create toggle button
    const toggleBtn = document.createElement('button');
    toggleBtn.type = 'button';
    toggleBtn.className = 'toggle-password';
    toggleBtn.title = 'Show/Hide Password';
    toggleBtn.innerHTML = '<i class="fa-solid fa-eye"></i>';
    
    // Add toggle functionality
    toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        togglePasswordVisibility(inputField, toggleBtn);
    });
    
    // Add styles if not in input-group
    if (!inputField.parentElement.classList.contains('input-group')) {
        inputField.style.paddingRight = '50px';
        inputField.parentElement.style.position = 'relative';
    }
    
    // Append toggle button after password field
    inputField.parentElement.appendChild(toggleBtn);
}

/**
 * Toggle password visibility
 */
function togglePasswordVisibility(inputField, toggleBtn) {
    const type = inputField.getAttribute('type') === 'password' ? 'text' : 'password';
    inputField.setAttribute('type', type);
    
    // Update icon
    if (toggleBtn) {
        toggleBtn.innerHTML = type === 'password' 
            ? '<i class="fa-solid fa-eye"></i>' 
            : '<i class="fa-solid fa-eye-slash"></i>';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializePasswordToggles();
});

// Also support dynamic forms added after page load
if (window.MutationObserver) {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                // Re-initialize password toggles for new nodes
                initializePasswordToggles();
            }
        });
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
}
