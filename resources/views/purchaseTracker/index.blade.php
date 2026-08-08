@extends('layouts.app')

@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<title>Purchase Tracker</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/purcreate.css') }}">
<script src="{{ asset('js/purIndex.js') }}"></script>

<div class="main-content">
    <div class="page-header">
        <h2><i class="fas fa-receipt"></i> Purchase Trackers</h2>
        <a href="{{ route('purchaseTracker.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add Purchase
        </a>
    </div>

<!-- Stats Cards - Top Section -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
            <i class="fas fa-receipt"></i>
        </div>
        <div class="stat-info">
            <span class="stat-label">Total Purchases</span>
            <span class="stat-value">{{ $totalPurchases ?? $purchases->total() }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7; color: #166534;">
            <i class="fas fa-building"></i>
        </div>
        <div class="stat-info">
            <span class="stat-label">NEBG</span>
            <div class="stat-double-row">
                <span class="stat-value">{{ $nebgCount ?? $purchases->where('company', 'NEBG')->count() }}</span>
                <span class="stat-divider">|</span>
                <span class="stat-amount">₱{{ number_format($nebgAmount ?? $purchases->where('company', 'NEBG')->sum('grand_total'), 2) }}</span>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe; color: #1e40af;">
            <i class="fas fa-building"></i>
        </div>
        <div class="stat-info">
            <span class="stat-label">FA</span>
            <div class="stat-double-row">
                <span class="stat-value">{{ $faCount ?? $purchases->where('company', 'FA')->count() }}</span>
                <span class="stat-divider">|</span>
                <span class="stat-amount">₱{{ number_format($faAmount ?? $purchases->where('company', 'FA')->sum('grand_total'), 2) }}</span>
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #92400e;">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-info">
            <span class="stat-label">Total Expenses</span>
            <span class="stat-value">₱{{ number_format($totalAmount ?? $purchases->sum('grand_total'), 2) }}</span>
        </div>
    </div>
</div>

    <!-- Search & Filter Section -->
    <div class="search-section">
        <form action="{{ route('purchaseTracker.index') }}" method="GET" class="search-form">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search by purchase name, vendor, receipt number..." 
                    value="{{ request('search') }}"
                    class="search-input"
                >
                
                <!-- Company Filter -->
                <select name="company" class="filter-select">
                    <option value="">All Companies</option>
                    <option value="NEBG" {{ request('company') == 'NEBG' ? 'selected' : '' }}>NEBG</option>
                    <option value="FA" {{ request('company') == 'FA' ? 'selected' : '' }}>FA</option>
                </select>

                <!-- Date Range Filter -->
                <input type="date" name="date_from" class="filter-date" value="{{ request('date_from') }}" placeholder="From">
                <span class="date-separator">to</span>
                <input type="date" name="date_to" class="filter-date" value="{{ request('date_to') }}" placeholder="To">

                <button type="submit" class="btn btn-search">
                    <i class="fas fa-search"></i> Search
                </button>
                
                @if(request('search') || request('company') || request('date_from') || request('date_to'))
                    <a href="{{ route('purchaseTracker.index') }}" class="btn btn-clear">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Purchases Table -->
    <div class="table-container">
        <div class="table-header-info">
            <span class="record-count">Showing {{ $purchases->firstItem() ?? 0 }} to {{ $purchases->lastItem() ?? 0 }} of {{ $purchases->total() }} records</span>
            <span class="sort-info">
                <i class="fas fa-sort-amount-down"></i> Latest entries first
            </span>
        </div>

        @if($purchases->count() > 0)
            <table class="purchases-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Purchase Date</th>
                        <th>Purchase Details</th>
                        <th>Company</th>
                        <th>Vendor</th>
                        <th>Receipt #</th>
                        <th>Grand Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $purchase)
                        <tr>
                            <td>{{ $loop->iteration + ($purchases->currentPage() - 1) * $purchases->perPage() }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('M d, Y') }}</td>
                            <td>
                                <span class="purchase-name">{{ $purchase->purachase_name }}</span>
                                @if($purchase->remarks)
                                    <span class="has-remarks" title="{{ $purchase->remarks }}">
                                        <i class="fas fa-comment-dots"></i>
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="company-badge company-{{ strtolower($purchase->company) }}">
                                    {{ $purchase->company }}
                                </span>
                            </td>
                            <td>{{ $purchase->vendor }}</td>
                            <td>
                                @if($purchase->receipt_number)
                                    <span class="receipt-number">{{ $purchase->receipt_number }}</span>
                                    @if($purchase->receipt_date)
                                        <br><small class="receipt-date">{{ \Carbon\Carbon::parse($purchase->receipt_date)->format('M d, Y') }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="grand-total">₱{{ number_format($purchase->grand_total, 2) }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                               <a href="javascript:void(0)" onclick="viewPurchase({{ $purchase->id }})" class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                <a href="{{ route('purchaseTracker.edit', $purchase->id) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                    <form action="{{ route('purchaseTracker.destroy', $purchase->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this purchase record?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $purchases->appends([
                    'search' => request('search'), 
                    'company' => request('company'),
                    'date_from' => request('date_from'),
                    'date_to' => request('date_to')
                ])->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-receipt"></i>
                <p>No purchase records found.</p>
                @if(request('search') || request('company') || request('date_from') || request('date_to'))
                    <p class="empty-subtext">Try adjusting your search or filter criteria.</p>
                @else
                    <a href="{{ route('purchaseTracker.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Add your first purchase
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
<div class="modal fade" id="viewPurchaseModal" tabindex="-1" role="dialog" aria-labelledby="viewPurchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPurchaseModalLabel">
                    <i class="fas fa-file-invoice"></i> 
                    <span id="modal-purchase-name">Purchase Details</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <!-- Content will be loaded via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading purchase details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                </button>
                    <a href="#" id="modal-edit-link" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Purchase
                    </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewPurchase(id) {
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('viewPurchaseModal'));
        modal.show();
    document.getElementById('modal-edit-link').href =
    `/purchaseTracker/${id}/edit`;
    // Show loading
    document.getElementById('modal-body-content').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading purchase details...</p>
        </div>
    `;
    
    // Fetch purchase details via AJAX
    fetch(`/purchaseTracker/${id}/modal`)
        .then(response => response.json())
        .then(data => {
            // Update modal content
            document.getElementById('modal-purchase-name').textContent = data.purachase_name;
            document.getElementById('modal-edit-link').href = `/purchaseTracker/${id}/edit`;
            
            // Build items HTML
            let itemsHtml = '';
            if (data.items && data.items.length > 0) {
                data.items.forEach((item, index) => {
                    itemsHtml += `
                        <div class="item-row">
                            <div class="item-number">${index + 1}</div>
                            <div class="item-details">
                                <div class="item-description">${item.description}</div>
                                <div class="item-meta">
                                    <span class="item-price">₱${parseFloat(item.unit_price).toFixed(2)}</span>
                                    <span class="item-qty">× ${item.quantity}</span>
                                    <span class="item-subtotal">₱${parseFloat(item.subtotal).toFixed(2)}</span>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                itemsHtml = `
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <p>No items found for this purchase</p>
                    </div>
                `;
            }
            
            // Build the modal content
            document.getElementById('modal-body-content').innerHTML = `
                <div class="modal-purchase-content">
                    <!-- Purchase Info -->
                    <div class="modal-section">
                        <div class="section-title">
                            <i class="fas fa-info-circle"></i> Purchase Information
                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Purchase Details</span>
                                <span class="info-value">${data.purachase_name}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Purchase Date</span>
                                <span class="info-value">${new Date(data.purchase_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Company</span>
                                <span class="info-value"><span class="badge badge-${data.company.toLowerCase()}">${data.company}</span></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Vendor</span>
                                <span class="info-value">${data.vendor}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Items Section -->
                    <div class="modal-section">
                        <div class="section-title">
                            <i class="fas fa-list"></i> Purchase Items <span class="item-badge">${data.items ? data.items.length : 0} items</span>
                        </div>
                        <div class="items-list">
                            ${itemsHtml}
                        </div>
                    </div>
                    
                    <!-- Summary -->
                    <div class="modal-section">
                        <div class="items-summary">
                            <div class="summary-row">
                                <span class="summary-label">Total Items:</span>
                                <span class="summary-value">${data.items ? data.items.length : 0}</span>
                            </div>
                            <div class="summary-row grand-total-row">
                                <span class="summary-label">Grand Total:</span>
                                <span class="summary-value grand-total"> ₱${Number(data.grand_total).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                            </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Receipt Info -->
                    ${data.receipt_number ? `
                    <div class="modal-section">
                        <div class="section-title">
                            <i class="fas fa-receipt"></i> Receipt Information
                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Receipt Number</span>
                                <span class="info-value">${data.receipt_number}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Delivery Date</span>
                                <span class="info-value">${data._date ? new Date(data.receipt_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A'}</span>
                            </div>
                        </div>
                        ${data.receipt_details ? `
                        <div class="info-item full-width">
                            <span class="info-label">Additional Details (Optional)</span>
                            <span class="info-value">${data.receipt_details}</span>
                        </div>` : ''}
                    </div>` : ''}
                    
                    <!-- Personnel -->
                    <div class="modal-section">
                        <div class="section-title">
                            <i class="fas fa-user-friends"></i> Personnel
                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Encoded By</span>
                                <span class="info-value">${data.receipt_encoded_by}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Received By</span>
                                <span class="info-value">${data.received_by || 'N/A'}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Pickup By</span>
                                <span class="info-value">${data.pickup_by || 'N/A'}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Bought By</span>
                                <span class="info-value">${data.bought_by || 'N/A'}</span>
                            </div>
                        </div>
                    </div>
                    
                    ${data.remarks ? `
                    <div class="modal-section">
                        <div class="section-title">
                            <i class="fas fa-comment"></i> Remarks
                        </div>
                        <p class="remark-text">${data.remarks}</p>
                    </div>` : ''}
                </div>
            `;
            
            // Add CSS for modal content
            const style = document.createElement('style');
            style.textContent = `
             .modal-purchase-content {
                padding: 20px 24px;
            }

            body.dark-mode .modal-purchase-content {
                background: #262c38;
            }

            .modal-section {
                margin-bottom: 24px;
            }

            .modal-section:last-child {
                margin-bottom: 0;
            }

            .section-title {
                font-size: 0.85rem;
                font-weight: 600;
                color: #1f2937;
                margin-bottom: 12px;
                padding-bottom: 8px;
                border-bottom: 2px solid #f3f4f6;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: color 0.2s ease, border-color 0.2s ease;
            }

            body.dark-mode .section-title {
                color: #eef2f8;
                border-bottom-color: #3f4a5a;
            }

            .section-title i {
                color: #2563eb;
            }

            .item-badge {
                font-size: 0.7rem;
                background: #f3f4f6;
                padding: 2px 8px;
                border-radius: 10px;
                color: #6b7280;
                font-weight: 500;
                transition: background 0.2s ease, color 0.2s ease;
            }

            body.dark-mode .item-badge {
                background: #1e232e;
                color: #94a3b8;
            }

            .info-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }

            .info-item {
                display: flex;
                flex-direction: column;
                gap: 2px;
            }

            .info-item.full-width {
                grid-column: 1 / -1;
            }

            .info-label {
                font-size: 0.7rem;
                color: #6b7280;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                transition: color 0.2s ease;
            }

            body.dark-mode .info-label {
                color: #94a3b8;
            }

            .info-value {
                font-size: 0.9rem;
                color: #1f2937;
                font-weight: 500;
                transition: color 0.2s ease;
            }

            body.dark-mode .info-value {
                color: #eef2f8;
            }

            .items-list {
                max-height: 300px;
                overflow-y: auto;
            }

            body.dark-mode .items-list::-webkit-scrollbar {
                width: 6px;
            }

            body.dark-mode .items-list::-webkit-scrollbar-track {
                background: #1e232e;
            }

            body.dark-mode .items-list::-webkit-scrollbar-thumb {
                background: #3f4a5a;
                border-radius: 3px;
            }

            .item-row {
                display: flex;
                gap: 12px;
                padding: 10px 0;
                border-bottom: 1px solid #f3f4f6;
                transition: border-color 0.2s ease;
            }

            body.dark-mode .item-row {
                border-bottom-color: #3f4a5a;
            }

            .item-row:last-child {
                border-bottom: none;
            }

            .item-number {
                width: 24px;
                height: 24px;
                background: #f3f4f6;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.7rem;
                font-weight: 600;
                color: #6b7280;
                flex-shrink: 0;
                transition: background 0.2s ease, color 0.2s ease;
            }

            body.dark-mode .item-number {
                background: #1e232e;
                color: #94a3b8;
            }

            .item-details {
                flex: 1;
                min-width: 0;
            }

            .item-description {
                font-size: 0.85rem;
                font-weight: 500;
                color: #1f2937;
                margin-bottom: 2px;
                transition: color 0.2s ease;
            }

            body.dark-mode .item-description {
                color: #eef2f8;
            }

            .item-meta {
                display: flex;
                gap: 12px;
                font-size: 0.75rem;
                color: #6b7280;
                transition: color 0.2s ease;
            }

            body.dark-mode .item-meta {
                color: #94a3b8;
            }

            .item-price {
                font-weight: 500;
                color: #4b5563;
                transition: color 0.2s ease;
            }

            body.dark-mode .item-price {
                color: #d0dbe8;
            }

            .item-qty {
                color: #9ca3af;
            }

            body.dark-mode .item-qty {
                color: #64748b;
            }

            .item-subtotal {
                font-weight: 600;
                color: #059669;
                transition: color 0.2s ease;
            }

            body.dark-mode .item-subtotal {
                color: #6ee7a0;
            }

            .items-summary {
                margin-top: 12px;
                padding-top: 12px;
                border-top: 1px solid #e5e7eb;
                transition: border-color 0.2s ease;
            }

            body.dark-mode .items-summary {
                border-top-color: #3f4a5a;
            }

            .summary-row {
                display: flex;
                justify-content: space-between;
                padding: 4px 0;
                font-size: 0.85rem;
            }

            .summary-label {
                color: #6b7280;
                transition: color 0.2s ease;
            }

            body.dark-mode .summary-label {
                color: #94a3b8;
            }

            .summary-value {
                font-weight: 500;
                color: #1f2937;
                transition: color 0.2s ease;
            }

            body.dark-mode .summary-value {
                color: #eef2f8;
            }

            .summary-row.grand-total-row {
                padding-top: 8px;
                margin-top: 4px;
                border-top: 1px solid #e5e7eb;
                font-size: 0.95rem;
                transition: border-color 0.2s ease;
            }

            body.dark-mode .summary-row.grand-total-row {
                border-top-color: #3f4a5a;
            }

            .summary-row.grand-total-row .summary-value {
                font-weight: 700;
                color: #059669;
                font-size: 1rem;
                transition: color 0.2s ease;
            }

            body.dark-mode .summary-row.grand-total-row .summary-value {
                color: #6ee7a0;
            }

            .badge-nebg {
                background: #dbeafe;
                color: #1e40af;
                padding: 2px 10px;
                border-radius: 10px;
                font-size: 0.75rem;
                font-weight: 600;
                transition: background 0.2s ease, color 0.2s ease;
            }

            body.dark-mode .badge-nebg {
                background: #1a2a4a;
                color: #60a5fa;
            }

            .badge-fa {
                background: #dcfce7;
                color: #166534;
                padding: 2px 10px;
                border-radius: 10px;
                font-size: 0.75rem;
                font-weight: 600;
                transition: background 0.2s ease, color 0.2s ease;
            }

            body.dark-mode .badge-fa {
                background: #1a3a2a;
                color: #6ee7a0;
            }

            .remark-text {
                margin: 0;
                padding: 8px 12px;
                background: #f9fafb;
                border-radius: 4px;
                color: #4b5563;
                font-size: 0.85rem;
                line-height: 1.5;
                transition: background 0.2s ease, color 0.2s ease;
            }

            body.dark-mode .remark-text {
                background: #1e232e;
                color: #d0dbe8;
            }

            .empty-state {
                text-align: center;
                padding: 20px;
                color: #9ca3af;
                transition: color 0.2s ease;
            }

            body.dark-mode .empty-state {
                color: #64748b;
            }

            .empty-state i {
                font-size: 2rem;
                display: block;
                margin-bottom: 8px;
            }

            body.dark-mode .empty-state i {
                color: #3f4a5a;
            }

            .empty-state p {
                margin: 0;
                font-size: 0.85rem;
            }

            .grand-total {
                font-size: 1.1rem !important;
            }

            /* Modal container dark mode support */
            body.dark-mode .modal-content {
                background: #262c38;
                border-color: #3f4a5a;
            }

            body.dark-mode .modal-header {
                background: #1e232e;
                border-bottom-color: #3f4a5a;
            }

            body.dark-mode .modal-header .modal-title {
                color: #eef2f8;
            }

            body.dark-mode .modal-footer {
                background: #1e232e;
                border-top-color: #3f4a5a;
            }

            body.dark-mode .modal-footer .btn-secondary {
                background: #343e50;
                color: #d0dbe8;
                border: none;
            }

            body.dark-mode .modal-footer .btn-secondary:hover {
                background: #3f4b60;
                color: #eef2f8;
            }

            body.dark-mode .modal-footer .btn-primary {
                background: #2563eb;
                border: none;
            }

            body.dark-mode .modal-footer .btn-primary:hover {
                background: #1d4ed8;
            }

            body.dark-mode .btn-close {
                filter: invert(1) brightness(2);
            }
            `;
            document.head.appendChild(style);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modal-body-content').innerHTML = `
                <div class="text-center py-5 text-danger">
                    <i class="fas fa-exclamation-circle fa-2x"></i>
                    <p class="mt-2">Failed to load purchase details. Please try again.</p>
                </div>
            `;
        });
}

</script>
@endsection