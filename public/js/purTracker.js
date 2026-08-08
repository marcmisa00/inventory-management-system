document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate and format grand total (if needed)
    const grandTotalInput = document.querySelector('input[name="grand_total"]');
    if (grandTotalInput) {
        grandTotalInput.addEventListener("blur", function() {
            let value = parseFloat(this.value);
            if (!isNaN(value)) {
                this.value = value.toFixed(2);
            }
        });
    }

    // Auto-set encoded by if logged in user
    const encodedByInput = document.querySelector('input[name="receipt_encoded_by"]');
    if (encodedByInput && !encodedByInput.value) {
        // If no user is logged in, you can set a default or leave empty
    }

    // Quick action: Reset form with confirmation
    document.querySelector('.quick-action-btn .fa-sync')?.closest('.quick-action-btn')?.addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to reset all form fields?')) {
            resetForm();
        }
    });

    // Keyboard shortcut: Ctrl+S to save
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            document.getElementById('purchase-form').submit();
        }
    });

    // Initialize items - Check for old items from validation errors
    if (typeof window.oldItems !== 'undefined' && window.oldItems && window.oldItems.length > 0) {
        window.oldItems.forEach((item, index) => {
            if (index > 0) {
                addItemRow();
            }
            const rows = document.querySelectorAll("#items-body tr");
            if (rows[index]) {
                const row = rows[index];
                const descInput = row.querySelector('input[name$="[description]"]');
                const priceInput = row.querySelector('input[name$="[unit_price]"]');
                const qtyInput = row.querySelector('input[name$="[quantity]"]');
                
                if (descInput) descInput.value = item.description || '';
                if (priceInput) priceInput.value = item.unit_price || '';
                if (qtyInput) qtyInput.value = item.quantity || '';
                
                updateRowSubtotal(row);
            }
        });
        updateGrandTotal();
    } else {
        // Default: add one empty row
        addItemRow();
    }
});

let itemCounter = 0;

function addItemRow() {
    const tbody = document.getElementById('items-body');
    if (!tbody) return;
    
    const row = document.createElement('tr');
    const rowIndex = tbody.children.length;
    const timestamp = Date.now() + rowIndex;
    
    row.innerHTML = `
        <td>
            <span class="row-number">${rowIndex + 1}</span>
        </td>
        <td>
            <input type="text" 
                   name="items[${rowIndex}][description]" 
                   class="item-input"
                   placeholder="Item description"
                   required>
        </td>
        <td>
            <input type="number" 
                   name="items[${rowIndex}][unit_price]" 
                   class="item-input price-input"
                   step="0.01"
                   min="0"
                   placeholder="0.00"
                   onchange="updateRowSubtotal(this.closest('tr'))"
                   onkeyup="updateRowSubtotal(this.closest('tr'))"
                   required>
        </td>
        <td>
            <input type="number" 
                   name="items[${rowIndex}][quantity]" 
                   class="item-input quantity-input"
                   min="1"
                   placeholder="1"
                   onchange="updateRowSubtotal(this.closest('tr'))"
                   onkeyup="updateRowSubtotal(this.closest('tr'))"
                   required>
        </td>
        <td>
            <span class="subtotal-display" id="subtotal-${timestamp}">₱0.00</span>
            <input type="hidden" name="items[${rowIndex}][subtotal]" value="0">
        </td>
        <td>
            <button type="button" class="remove-item-btn" onclick="removeItemRow(this)" ${rowIndex === 0 ? 'disabled style="opacity:0.5;cursor:not-allowed"' : ''}>
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    updateItemCount();
    
    // Focus the description input
    const descInput = row.querySelector('input[name$="[description]"]');
    if (descInput) setTimeout(() => descInput.focus(), 100);
}

function renumberInputs() {
    const rows = document.querySelectorAll('#items-body tr');
    rows.forEach((row, index) => {
        const descInput = row.querySelector('input[name$="[description]"]');
        const priceInput = row.querySelector('input[name$="[unit_price]"]');
        const qtyInput = row.querySelector('input[name$="[quantity]"]');
        const subtotalInput = row.querySelector('input[name$="[subtotal]"]');
        
        if (descInput) descInput.name = `items[${index}][description]`;
        if (priceInput) priceInput.name = `items[${index}][unit_price]`;
        if (qtyInput) qtyInput.name = `items[${index}][quantity]`;
        if (subtotalInput) subtotalInput.name = `items[${index}][subtotal]`;
        
        // Update row number
        const numSpan = row.querySelector('.row-number');
        if (numSpan) numSpan.textContent = index + 1;
        
        // Update remove button state
        const removeBtn = row.querySelector('.remove-item-btn');
        if (removeBtn) {
            if (index === 0) {
                removeBtn.disabled = true;
                removeBtn.style.opacity = '0.5';
                removeBtn.style.cursor = 'not-allowed';
            } else {
                removeBtn.disabled = false;
                removeBtn.style.opacity = '1';
                removeBtn.style.cursor = 'pointer';
            }
        }
    });
}

function removeItemRow(button) {
    const row = button.closest('tr');
    const tbody = document.getElementById('items-body');
    
    // Don't remove if it's the last row
    if (tbody.children.length <= 1) {
        // Just clear the row instead
        row.querySelectorAll('.item-input').forEach(input => input.value = '');
        updateRowSubtotal(row);
        updateGrandTotal();
        return;
    }
    
    row.remove();
    renumberInputs();
    updateItemCount();
    updateGrandTotal();
}

function updateRowNumbers() {
    // This function is now handled by renumberInputs()
    renumberInputs();
}

function updateRowSubtotal(row) {
    if (!row) return;
    
    const priceInput = row.querySelector('input[name$="[unit_price]"]');
    const qtyInput = row.querySelector('input[name$="[quantity]"]');
    const subtotalDisplay = row.querySelector('.subtotal-display');
    const subtotalHidden = row.querySelector('input[name$="[subtotal]"]');
    
    if (!priceInput || !qtyInput) return;
    
    const price = parseFloat(priceInput.value) || 0;
    const qty = parseInt(qtyInput.value) || 0;
    const subtotal = price * qty;
    
    if (subtotalDisplay) {
        subtotalDisplay.textContent = `₱${subtotal.toFixed(2)}`;
    }
    if (subtotalHidden) {
        subtotalHidden.value = subtotal.toFixed(2);
    }
    
    updateGrandTotal();
}

function updateGrandTotal() {
    const subtotalHiddenInputs = document.querySelectorAll('input[name$="[subtotal]"]');
    let grandTotal = 0;
    
    subtotalHiddenInputs.forEach(input => {
        grandTotal += parseFloat(input.value) || 0;
    });
    
    const display = document.getElementById('grand-total-display');
    const hidden = document.getElementById('grand-total-input');
    const grandTotalInput = document.querySelector('input[name="grand_total"]');
    
    if (display) {
        display.textContent = `₱${grandTotal.toFixed(2)}`;
    }
    if (hidden) {
        hidden.value = grandTotal.toFixed(2);
    }
    if (grandTotalInput) {
        grandTotalInput.value = grandTotal.toFixed(2);
    }
}

function updateItemCount() {
    const count = document.querySelectorAll('#items-body tr').length;
    const countDisplay = document.getElementById('item-count');
    if (countDisplay) {
        countDisplay.textContent = `${count} item${count > 1 ? 's' : ''}`;
    }
}

function resetForm() {
    if (!confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
        return;
    }
    
    // Clear all inputs
    document.querySelectorAll('.erp-input, .erp-textarea').forEach(input => {
        if (input.type !== 'hidden' && input.type !== 'submit' && input.type !== 'button') {
            input.value = '';
        }
    });
    
    // Reset items - keep only one empty row
    const tbody = document.getElementById('items-body');
    if (tbody) {
        tbody.innerHTML = '';
        addItemRow();
        renumberInputs();
    }
    
    // Reset date to today
    const dateInput = document.querySelector('[name="purchase_date"]');
    if (dateInput) {
        dateInput.value = new Date().toISOString().split('T')[0];
    }
    
    // Reset grand total display
    updateGrandTotal();
}

// Form validation before submit
const form = document.getElementById('purchase-form');
if (form) {
    form.addEventListener('submit', function(e) {
        const items = document.querySelectorAll('#items-body tr');
        let hasItems = false;
        let isValid = true;
        let firstInvalid = null;
        
        items.forEach(row => {
            const desc = row.querySelector('input[name$="[description]"]');
            const price = row.querySelector('input[name$="[unit_price]"]');
            const qty = row.querySelector('input[name$="[quantity]"]');
            
            // Check if any item has data
            const hasDesc = desc && desc.value.trim();
            const hasPrice = price && price.value && parseFloat(price.value) > 0;
            const hasQty = qty && qty.value && parseInt(qty.value) > 0;
            
            if (hasDesc || hasPrice || hasQty) {
                hasItems = true;
                
                // Validate individual fields
                if (!hasDesc) {
                    if (desc) {
                        desc.classList.add('is-invalid');
                        if (!firstInvalid) firstInvalid = desc;
                    }
                    isValid = false;
                } else if (desc) {
                    desc.classList.remove('is-invalid');
                }
                
                if (!hasPrice) {
                    if (price) {
                        price.classList.add('is-invalid');
                        if (!firstInvalid) firstInvalid = price;
                    }
                    isValid = false;
                } else if (price) {
                    price.classList.remove('is-invalid');
                }
                
                if (!hasQty) {
                    if (qty) {
                        qty.classList.add('is-invalid');
                        if (!firstInvalid) firstInvalid = qty;
                    }
                    isValid = false;
                } else if (qty) {
                    qty.classList.remove('is-invalid');
                }
            }
        });
        
        if (!hasItems) {
            alert('Please add at least one item to the purchase.');
            e.preventDefault();
            return false;
        }
        
        if (!isValid) {
            alert('Please fill in all required fields for each item.');
            e.preventDefault();
            if (firstInvalid) {
                firstInvalid.focus();
            }
            return false;
        }
        
        return true;
    });
}

// Remove invalid class on input
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('is-invalid')) {
        e.target.classList.remove('is-invalid');
    }
});