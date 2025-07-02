document.addEventListener('DOMContentLoaded', function() {
    // Initialize date pickers
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');
    const dailyRate = document.querySelector('.daily-rate')?.textContent || 0;
    const totalCostElement = document.querySelector('.total-cost');
    
    if (startDateInput && endDateInput) {
        // Set minimum dates
        const today = new Date().toISOString().split('T')[0];
        startDateInput.min = today;
        endDateInput.min = today;
        
        // Update end date minimum when start date changes
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            calculateTotal();
        });
        
        endDateInput.addEventListener('change', calculateTotal);
        
        function calculateTotal() {
            if (startDateInput.value && endDateInput.value) {
                const start = new Date(startDateInput.value);
                const end = new Date(endDateInput.value);
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                const totalCost = diffDays * parseFloat(dailyRate);
                
                if (totalCostElement) {
                    totalCostElement.textContent = totalCost.toFixed(2);
                }
            }
        }
    }
    
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});