@extends('layouts.app')

@section('content')
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
  <title>Add Purchase · Multi-Item</title>
  <!-- Font & Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/create-purc.css') }}">


<div class="erp-main">
    <!-- Page Header -->
    <div class="erp-page-header">
        <div class="header-left">
            <div class="breadcrumb">
                <a href="{{ route('purchaseTracker.index') }}" class="breadcrumb-link">
                    <i class="fas fa-receipt"></i> Purchase Trackers
                </a>
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-current">New Purchase</span>
            </div>
            <h1 class="page-title">Create Purchase Record</h1>
            <p class="page-subtitle">Add multiple items with the same purchase date</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn-erp btn-erp-secondary" onclick="window.history.back()">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="submit" form="purchase-form" class="btn-erp btn-erp-primary">
                <i class="fas fa-save"></i> Save Purchase
            </button>
        </div>
    </div>

    <!-- Main Form -->
    <form id="purchase-form" action="{{ route('purchaseTracker.store') }}" method="POST">
        @csrf
        
        <div class="erp-form-grid">
            <!-- Left Column - Main Fields -->
            <div class="erp-main-column">
                <!-- Document Header Section -->
                <div class="erp-card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <i class="fas fa-file-invoice"></i>
                            <span class="card-title">Purchase Details</span>
                        </div>
                        <div class="card-header-right">
                            <span class="status-badge status-draft">DRAFT</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="erp-field-group erp-field-group-2">
                            <div class="erp-field">
                                <label class="field-label required">Purchase Name</label>
                                <input type="text" 
                                       name="purachase_name" 
                                       value="{{ old('purachase_name') }}"
                                       class="erp-input @error('purachase_name') is-invalid @enderror"
                                       placeholder="e.g., PR-2026-001 Office Supplies"
                                       required>
                                @error('purachase_name')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="erp-field">
                                <label class="field-label required">Purchase Date</label>
                                <input type="date" 
                                       name="purchase_date" 
                                       value="{{ old('purchase_date', date('Y-m-d')) }}"
                                       class="erp-input @error('purchase_date') is-invalid @enderror"
                                       required>
                                @error('purchase_date')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="erp-field-group erp-field-group-2">
                            <div class="erp-field">
                                <label class="field-label required">Company</label>
                                <div class="erp-select-wrapper">
                                    <select name="company" 
                                            class="erp-select @error('company') is-invalid @enderror"
                                            required>
                                        <option value="">Select Company</option>
                                        <option value="NEBG" {{ old('company') == 'NEBG' ? 'selected' : '' }}>
                                            <i class="fas fa-building"></i> NEBG
                                        </option>
                                        <option value="FA" {{ old('company') == 'FA' ? 'selected' : '' }}>
                                            <i class="fas fa-building"></i> FA
                                        </option>
                                    </select>
                                    <i class="fas fa-chevron-down select-arrow"></i>
                                </div>
                                @error('company')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="erp-field">
                                <label class="field-label required">Vendor</label>
                                <input type="text" 
                                       name="vendor" 
                                       value="{{ old('vendor') }}"
                                       class="erp-input @error('vendor') is-invalid @enderror"
                                       placeholder="Enter vendor/supplier name"
                                       required>
                                @error('vendor')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items Section - Multi-Item Input -->
                <div class="erp-card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <i class="fas fa-list"></i>
                            <span class="card-title">Purchase Items</span>
                            <span class="item-count" id="item-count">0 items</span>
                        </div>
                        <div class="card-header-right">
                            <button type="button" class="btn-erp-sm btn-erp-primary" onclick="addItemRow()">
                                <i class="fas fa-plus"></i> Add Item
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="items-table" id="items-table">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 35%">Description</th>
                                        <th style="width: 15%">Unit Price (₱)</th>
                                        <th style="width: 12%">Quantity</th>
                                        <th style="width: 18%">Subtotal (₱)</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="items-body">
                                    <!-- Items will be added here dynamically -->
                                </tbody>
                                <tfoot>
                                    <tr class="total-row">
                                        <td colspan="4" class="text-right"><strong>Grand Total</strong></td>
                                        <td colspan="2">
                                            <strong id="grand-total-display">₱0.00</strong>
                                            <input type="hidden" name="grand_total" id="grand-total-input" value="0">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Receipt Section -->
                <div class="erp-card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <i class="fas fa-receipt"></i>
                            <span class="card-title">Receipt Details</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="erp-field-group erp-field-group-2">
                            <div class="erp-field">
                                <label class="field-label">Receipt Number</label>
                                <input type="text" 
                                       name="receipt_number" 
                                       value="{{ old('receipt_number') }}"
                                       class="erp-input @error('receipt_number') is-invalid @enderror"
                                       placeholder="e.g., INV-2026-001">
                                @error('receipt_number')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="erp-field">
                                <label class="field-label">Delivery Date</label>
                                <input type="date" 
                                       name="receipt_date" 
                                       value="{{ old('receipt_date') }}"
                                       class="erp-input @error('receipt_date') is-invalid @enderror">
                                @error('receipt_date')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="erp-field-group">
                            <div class="erp-field">
                                <label class="field-label">Additional Details (Optional)</label>
                                <textarea name="receipt_details" 
                                          class="erp-textarea @error('receipt_details') is-invalid @enderror"
                                          rows="3"
                                          placeholder="Enter detailed description of items received">{{ old('receipt_details') }}</textarea>
                                @error('receipt_details')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="erp-sidebar">
                <!-- Quick Actions -->
                <div class="erp-card sidebar-card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <i class="fas fa-bolt"></i>
                            <span class="card-title">Quick Actions</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <button type="button" class="quick-action-btn" onclick="window.location.href='{{ route('purchaseTracker.index') }}'">
                                <i class="fas fa-list"></i>
                                <span>View All</span>
                            </button>
                            <button type="button" class="quick-action-btn" onclick="resetForm()">
                                <i class="fas fa-sync"></i>
                                <span>Reset Form</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Personnel Section -->
                <div class="erp-card sidebar-card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <i class="fas fa-user-friends"></i>
                            <span class="card-title">Personnel</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="erp-field sidebar-field">
                            <label class="field-label required">Encoded By</label>
                            <input type="text"
                                name="receipt_encoded_by" 
                                value="{{ old('receipt_encoded_by', auth()->user()->employeeProfile->lastname . ', ' . auth()->user()->employeeProfile->firstname) }}"
                                class="erp-input @error('receipt_encoded_by') is-invalid @enderror"
                                placeholder="Encoder name"
                                readonly >
                            @error('receipt_encoded_by')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="erp-field sidebar-field">
                            <label class="field-label">Received By</label>
                            <input type="text" 
                                   name="received_by" 
                                   value="{{ old('received_by') }}"
                                   class="erp-input @error('received_by') is-invalid @enderror"
                                   placeholder="Receiver name">
                            @error('received_by')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="erp-field sidebar-field">
                            <label class="field-label">Pickup By</label>
                            <input type="text" 
                                   name="pickup_by" 
                                   value="{{ old('pickup_by') }}"
                                   class="erp-input @error('pickup_by') is-invalid @enderror"
                                   placeholder="Pickup person">
                            @error('pickup_by')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="erp-field sidebar-field">
                            <label class="field-label">Bought By</label>
                            <input type="text" 
                                   name="bought_by" 
                                   value="{{ old('bought_by') }}"
                                   class="erp-input @error('bought_by') is-invalid @enderror"
                                   placeholder="Buyer name">
                            @error('bought_by')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Remarks -->
                <div class="erp-card sidebar-card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <i class="fas fa-comment"></i>
                            <span class="card-title">Remarks</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <textarea name="remarks" 
                                  class="erp-textarea sidebar-textarea @error('remarks') is-invalid @enderror"
                                  rows="4"
                                  placeholder="Additional notes or comments...">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


<script>
window.oldItems = @json(old('items', []));
</script>

<script src="{{ asset('js/purTracker.js') }}"></script>

@endsection