// Modal handlers and other interactive elements
document.addEventListener('DOMContentLoaded', function() {
    // Success/error message auto-dismiss
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // Image preview for file uploads
    const fileInput = document.querySelector('input[type="file"][accept="image/*"]');
    if (fileInput) {
        const imagePreview = document.getElementById('imagePreview');
        
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                
                reader.addEventListener('load', function() {
                    imagePreview.src = this.result;
                    imagePreview.style.display = 'block';
                });
                
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Filter form submission with AJAX (optional)
    const filterForm = document.querySelector('.filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams(formData).toString();
            
            fetch(`${this.action}?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.querySelector('.car-listings').innerHTML = html;
            })
            .catch(error => console.error('Error:', error));
        });
    }
});