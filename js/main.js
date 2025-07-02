// Main application JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Load all scripts
    const scripts = [
        'rental.js',
        'validation.js',
        'interactive.js'
    ];
    
    scripts.forEach(script => {
        const scriptEl = document.createElement('script');
        scriptEl.src = `js/${script}`;
        document.body.appendChild(scriptEl);
    });
    
    // Initialize Bootstrap components
    const toastElList = [].slice.call(document.querySelectorAll('.toast'));
    const toastList = toastElList.map(function(toastEl) {
        return new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });
    });
    
    toastList.forEach(toast => toast.show());
    
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    const popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});