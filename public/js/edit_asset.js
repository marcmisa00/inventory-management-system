document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on first field
    const firstField = document.getElementById('asset_tag');
    if (firstField) {
        firstField.focus();
    }

    // Highlight status when changed
    const statusSelect = document.getElementById('status');
    const currentStatusIndicator = document.querySelector('.current-status-indicator');

    if (statusSelect && currentStatusIndicator) {
        statusSelect.addEventListener('change', function() {
            if (this.value) {
                // User selected a new status
                const selectedOption = this.options[this.selectedIndex];
                const statusText = selectedOption.text;
                
                // Update the indicator to show the selected status
                const currentBadge = currentStatusIndicator.querySelector('.status-badge');
                if (currentBadge) {
                    // Remove all status classes
                    currentBadge.className = 'status-badge';
                    // Add the new status class
                    currentBadge.className = 'status-badge status-' + statusText.toLowerCase().replace(/ /g, '-');
                    // Update the text
                    currentBadge.innerHTML = '<i class="fas fa-circle status-dot"></i> New: ' + statusText;
                    
                    // Update hint
                    const hint = currentStatusIndicator.querySelector('.status-hint');
                    if (hint) {
                        hint.innerHTML = '<i class="fas fa-sync-alt"></i> Status will be updated to: ' + statusText;
                        hint.style.color = '#2563eb';
                    }
                }
            } else {
                // Reset to current status
                const currentStatus = '{{ $asset->status }}';
                const currentBadge = currentStatusIndicator.querySelector('.status-badge');
                if (currentBadge) {
                    currentBadge.className = 'status-badge status-' + currentStatus.toLowerCase().replace(/ /g, '-');
                    currentBadge.innerHTML = '<i class="fas fa-circle status-dot"></i> Current: ' + currentStatus;
                    
                    const hint = currentStatusIndicator.querySelector('.status-hint');
                    if (hint) {
                        hint.innerHTML = '<i class="fas fa-info-circle"></i> Select a new status or keep the current one';
                        hint.style.color = '#94a3b8';
                    }
                }
            }
        });
    }

    // Confirm before leaving with unsaved changes
    let formChanged = false;
    const form = document.getElementById('editForm');
    const formInputs = form.querySelectorAll('input:not([type="hidden"]), select, textarea');

    formInputs.forEach(input => {
        input.addEventListener('change', function() {
            formChanged = true;
        });
        input.addEventListener('input', function() {
            formChanged = true;
        });
    });

    // Show warning when trying to leave with unsaved changes
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            return e.returnValue;
        }
    });

    // Reset form changed flag on submit
    form.addEventListener('submit', function() {
        formChanged = false;
    });
});