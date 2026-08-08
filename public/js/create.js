document.addEventListener('DOMContentLoaded', function() {
    // Update date/time
    function updateDateTime() {
        const now = new Date();
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        document.getElementById('currentDateTime').textContent = now.toLocaleDateString('en-US', options);
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // Progress tracking
    const requiredFields = document.querySelectorAll('[required]');
    const totalRequired = requiredFields.length;
    document.getElementById('totalRequired').textContent = totalRequired;

    function updateProgress() {
        let filled = 0;
        requiredFields.forEach(field => {
            if (field.value.trim() !== '') {
                filled++;
            }
        });
        document.getElementById('requiredCount').textContent = filled;
        const percentage = (filled / totalRequired) * 100;
        document.getElementById('progressFill').style.width = percentage + '%';
    }

    // Update progress on input change
    document.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('input', updateProgress);
        field.addEventListener('change', updateProgress);
    });
    updateProgress();

    // Auto-generate asset tag if empty
    const assetTagInput = document.getElementById('asset_tag');
    if (!assetTagInput.value) {
        const year = new Date().getFullYear();
        const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
        assetTagInput.placeholder = `AST-${year}-${random}`;
    }

    // Quick delivery date buttons
    window.setToday = function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('delivery_date').value = today;
        updateProgress();
    };

    window.clearDate = function() {
        document.getElementById('delivery_date').value = '';
        updateProgress();
    };

    // Form validation before submit
    document.getElementById('assetForm').addEventListener('submit', function(e) {
        const required = this.querySelectorAll('[required]');
        let valid = true;
        required.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                valid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        if (!valid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });

    // Reset button
    document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to reset the form?')) {
            document.getElementById('assetForm').reset();
            updateProgress();
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        }
    });
});