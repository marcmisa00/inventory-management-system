@extends('layouts.app')

@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<title>Edit Purchase</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/editPur.css') }}">

<div class="main-content">
    <div class="page-header">
        <h2><i class="fas fa-edit"></i> Edit Purchase</h2>
        <div class="header-actions">
            <a href="{{ route('purchaseTracker.index') }}" class="btn-erp btn-erp-primary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="form-container">
        <form action="{{ route('purchaseTracker.update', $purchase->id) }}" method="POST" id="purchaseForm">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                <!-- Left Column -->
                <div class="form-column">
                    <!-- Purchase Name -->
                    <div class="form-group">
                        <label for="purachase_name" class="form-label required">Purchase Details</label>
                        <input type="text" 
                               class="form-control @error('purachase_name') is-invalid @enderror" 
                               id="purachase_name" 
                               name="purachase_name" 
                               value="{{ old('purachase_name', $purchase->purachase_name) }}" 
                               required>
                        @error('purachase_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Purchase Date -->
                    <div class="form-group">
                        <label for="purchase_date" class="form-label required">Purchase Date</label>
                        <input type="date" 
                               class="form-control @error('purchase_date') is-invalid @enderror" 
                               id="purchase_date" 
                               name="purchase_date" 
                               value="{{ old('purchase_date', $purchase->purchase_date ? \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d') : '') }}" 
                               required>
                        @error('purchase_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Company -->
                    <div class="form-group">
                        <label for="company" class="form-label required">Company</label>
                        <select class="form-control @error('company') is-invalid @enderror" 
                                id="company" 
                                name="company" 
                                required>
                            <option value="">Select Company</option>
                            <option value="NEBG" {{ old('company', $purchase->company) == 'NEBG' ? 'selected' : '' }}>NEBG</option>
                            <option value="FA" {{ old('company', $purchase->company) == 'FA' ? 'selected' : '' }}>FA</option>
                        </select>
                        @error('company')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Vendor -->
                    <div class="form-group">
                        <label for="vendor" class="form-label required">Vendor</label>
                        <input type="text" 
                               class="form-control @error('vendor') is-invalid @enderror" 
                               id="vendor" 
                               name="vendor" 
                               value="{{ old('vendor', $purchase->vendor) }}" 
                               required>
                        @error('vendor')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Receipt Number -->
                    <div class="form-group">
                        <label for="receipt_number" class="form-label">Receipt Number</label>
                        <input type="text" 
                               class="form-control @error('receipt_number') is-invalid @enderror" 
                               id="receipt_number" 
                               name="receipt_number" 
                               value="{{ old('receipt_number', $purchase->receipt_number) }}">
                        @error('receipt_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Receipt Date -->
                    <div class="form-group">
                        <label for="receipt_date" class="form-label">Delivery Date</label>
                        <input type="date" 
                               class="form-control @error('Delivery_date') is-invalid @enderror" 
                               id="receipt_date" 
                               name="receipt_date" 
                               value="{{ old('receipt_date', $purchase->receipt_date ? \Carbon\Carbon::parse($purchase->receipt_date)->format('Y-m-d') : '') }}">
                        @error('receipt_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="form-column">
                    <!-- Receipt Details -->
                    <div class="form-group">
                        <label for="receipt_details" class="form-label">Receipt Details</label>
                        <textarea class="form-control @error('receipt_details') is-invalid @enderror" 
                                  id="receipt_details" 
                                  name="receipt_details" 
                                  rows="3">{{ old('receipt_details', $purchase->receipt_details) }}</textarea>
                        @error('receipt_details')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Personnel Section -->
                    <div class="personnel-section">
                        <h4 class="section-title"><i class="fas fa-user-friends"></i> Personnel</h4>
                        
                        <div class="form-group">
                            <label for="receipt_encoded_by" class="form-label">Encoded By</label>
                            <input type="text" 
                                   class="form-control @error('receipt_encoded_by') is-invalid @enderror" 
                                   id="receipt_encoded_by" 
                                   name="receipt_encoded_by" 
                                   value="{{ old('receipt_encoded_by', $purchase->receipt_encoded_by) }}"
                                   readonly>
                            @error('receipt_encoded_by')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="received_by" class="form-label">Received By</label>
                            <input type="text" 
                                   class="form-control @error('received_by') is-invalid @enderror" 
                                   id="received_by" 
                                   name="received_by" 
                                   value="{{ old('received_by', $purchase->received_by) }}">
                            @error('received_by')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="pickup_by" class="form-label">Pickup By</label>
                            <input type="text" 
                                   class="form-control @error('pickup_by') is-invalid @enderror" 
                                   id="pickup_by" 
                                   name="pickup_by" 
                                   value="{{ old('pickup_by', $purchase->pickup_by) }}">
                            @error('pickup_by')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="bought_by" class="form-label">Bought By</label>
                            <input type="text" 
                                   class="form-control @error('bought_by') is-invalid @enderror" 
                                   id="bought_by" 
                                   name="bought_by" 
                                   value="{{ old('bought_by', $purchase->bought_by) }}">
                            @error('bought_by')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Section -->
            <div class="items-section">
                <div class="items-header">
                    <h4 class="section-title"><i class="fas fa-list"></i> Purchase Items</h4>
                    <button type="button" class="btn btn-success btn-sm" onclick="addItem()">
                        <i class="fas fa-plus-circle"></i> Add Item
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="items-table" id="itemsTable">
                        <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 35%">Description</th>
                                <th style="width: 15%">Quantity</th>
                                <th style="width: 15%">Unit Price</th>
                                <th style="width: 20%">Subtotal</th>
                                <th style="width: 10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            @foreach($purchase->items as $index => $item)
                                <tr class="item-row">
                                    <td class="text-center item-number">{{ $index + 1 }}</td>
                                    <td>
                                        <input type="text" 
                                               class="form-control form-control-sm" 
                                               name="items[{{ $index }}][description]" 
                                               value="{{ old("items.{$index}.description", $item->description) }}" 
                                               placeholder="Item description" required>
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm quantity" 
                                               name="items[{{ $index }}][quantity]" 
                                               value="{{ old("items.{$index}.quantity", $item->quantity) }}" 
                                               min="1" 
                                               step="1" 
                                               placeholder="Qty" 
                                               onchange="calculateSubtotal(this)" required>
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm unit-price" 
                                               name="items[{{ $index }}][unit_price]" 
                                               value="{{ old("items.{$index}.unit_price", $item->unit_price) }}" 
                                               min="0" 
                                               step="0.01" 
                                               placeholder="0.00" 
                                               onchange="calculateSubtotal(this)" required>
                                    </td>
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm subtotal" 
                                               name="items[{{ $index }}][subtotal]" 
                                               value="{{ old("items.{$index}.subtotal", $item->subtotal) }}" 
                                               step="0.01" 
                                               readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Grand Total:</strong></td>
                                <td colspan="2">
                                    <div class="grand-total-display">
                                        ₱ <span id="grandTotal">{{ number_format($purchase->grand_total, 2) }}</span>
                                        <input type="hidden" name="grand_total" id="grandTotalInput" value="{{ $purchase->grand_total }}">
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Remarks -->
            <div class="form-group">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea class="form-control @error('remarks') is-invalid @enderror" 
                          id="remarks" 
                          name="remarks" 
                          rows="3">{{ old('remarks', $purchase->remarks) }}</textarea>
                @error('remarks')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Purchase
                </button>
                <a href="{{ route('purchaseTracker.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let itemCounter = {{ $purchase->items->count() }};

// Calculate subtotal for a single item
function calculateSubtotal(element) {
    const row = element.closest('.item-row');
    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
    const subtotal = quantity * unitPrice;
    
    row.querySelector('.subtotal').value = subtotal.toFixed(2);
    updateGrandTotal();
}

// Update grand total
function updateGrandTotal() {
    const subtotals = document.querySelectorAll('.subtotal');
    let grandTotal = 0;

    subtotals.forEach(input => {
        grandTotal += parseFloat(input.value) || 0;
    });

    document.getElementById('grandTotal').textContent =
        grandTotal.toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    // Keep the hidden input as a plain number for saving
    document.getElementById('grandTotalInput').value = grandTotal.toFixed(2);
}

// Add new item row
function addItem() {
    const tbody = document.getElementById('itemsBody');
    const rowCount = tbody.children.length;
    
    const row = document.createElement('tr');
    row.className = 'item-row';
    row.innerHTML = `
        <td class="text-center item-number">${rowCount + 1}</td>
        <td>
            <input type="text" 
                   class="form-control form-control-sm" 
                   name="items[${itemCounter}][description]" 
                   placeholder="Item description" required>
        </td>
        <td>
            <input type="number" 
                   class="form-control form-control-sm quantity" 
                   name="items[${itemCounter}][quantity]" 
                   min="1" 
                   step="1" 
                   placeholder="Qty" 
                   onchange="calculateSubtotal(this)" required>
        </td>
        <td>
            <input type="number" 
                   class="form-control form-control-sm unit-price" 
                   name="items[${itemCounter}][unit_price]" 
                   min="0" 
                   step="0.01" 
                   placeholder="0.00" 
                   onchange="calculateSubtotal(this)" required>
        </td>
        <td>
            <input type="number" 
                   class="form-control form-control-sm subtotal" 
                   name="items[${itemCounter}][subtotal]" 
                   step="0.01" 
                   readonly>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    itemCounter++;
    updateItemNumbers();
}

// Remove item row
function removeItem(button) {
    const row = button.closest('.item-row');
    if (document.querySelectorAll('.item-row').length > 1) {
        row.remove();
        updateItemNumbers();
        updateGrandTotal();
    } else {
        alert('At least one item is required.');
    }
}

// Update item numbers
function updateItemNumbers() {
    const rows = document.querySelectorAll('.item-row');
    rows.forEach((row, index) => {
        row.querySelector('.item-number').textContent = index + 1;
    });
}

// Auto-calculate on page load
document.addEventListener('DOMContentLoaded', function() {
    // Calculate all subtotals on load
    document.querySelectorAll('.quantity, .unit-price').forEach(input => {
        calculateSubtotal(input);
    });
});
</script>
@endsection