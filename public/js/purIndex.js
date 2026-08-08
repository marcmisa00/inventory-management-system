document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change (optional)
    const filterSelect = document.querySelector('.filter-select');
    const dateFrom = document.querySelector('input[name="date_from"]');
    const dateTo = document.querySelector('input[name="date_to"]');

    // Add change event listeners for auto-submit
    [filterSelect, dateFrom, dateTo].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                // Only auto-submit if there's a value selected
                const form = this.closest('form');
                if (this.value || (dateFrom.value && dateTo.value)) {
                    form.submit();
                }
            });
        }
    });

    // Set max date for date_to to today
    const today = new Date().toISOString().split('T')[0];
    if (dateTo) {
        dateTo.setAttribute('max', today);
    }
    if (dateFrom) {
        dateFrom.setAttribute('max', today);
    }
});