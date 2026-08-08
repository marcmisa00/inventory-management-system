@extends('layouts.app')

@section('content')
<div class="main-content">
    <div class="page-header">
        <div>
            <h2><i class="fas fa-file-import"></i> Import Purchase Orders</h2>
            <p class="page-subtitle">Import purchase orders with multiple items from Excel or CSV file</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('purchaseTracker.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Purchase Tracker
            </a>
        </div>
    </div>

    <div class="import-container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Instructions -->
                        <div class="import-instructions">
                            <h4><i class="fas fa-info-circle"></i> Instructions</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Purchase Order Header Information</h5>
                                    <ul>
                                        <li><strong>purchase_name</strong> - Purchase order name (Required)</li>
                                        <li><strong>purchase_date</strong> - Date of purchase (Required)</li>
                                        <li><strong>company</strong> - NEBG or FA (Required)</li>
                                        <li><strong>vendor</strong> - Supplier name (Required)</li>
                                        <li><strong>receipt_number</strong> - Receipt reference (Optional)</li>
                                        <li><strong>receipt_date</strong> - Receipt date (Optional)</li>
                                        <li><strong>receipt_details</strong> - Additional details (Optional)</li>
                                        <li><strong>remarks</strong> - General remarks (Optional)</li>
                                        <li><strong>receipt_encoded_by</strong> - Who encoded (Optional)</li>
                                        <li><strong>received_by</strong> - Who received (Optional)</li>
                                        <li><strong>pickup_by</strong> - Who picked up (Optional)</li>
                                        <li><strong>bought_by</strong> - Who bought (Optional)</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Item Information</h5>
                                    <ul>
                                        <li><strong>item_description</strong> - Item description (Required)</li>
                                        <li><strong>unit_price</strong> - Price per unit (Required)</li>
                                        <li><strong>quantity</strong> - Quantity (Required)</li>
                                    </ul>
                                    <div class="alert alert-info mt-3">
                                        <i class="fas fa-lightbulb"></i>
                                        <strong>Tip:</strong> Multiple rows with the same purchase details will be grouped into one purchase order with multiple items.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Template Download -->
                        <div class="template-download text-center">
                            <a href="{{ route('purchaseTracker.template') }}" class="btn btn-info btn-lg">
                                <i class="fas fa-download"></i> Download Template
                            </a>
                        </div>

                        <!-- Import Form -->
                        <form action="{{ route('purchaseTracker.import') }}" method="POST" enctype="multipart/form-data" class="import-form">
                            @csrf
                            
                            <div class="form-group">
                                <label for="file">Select File</label>
                                <div class="file-input-wrapper">
                                    <input type="file" 
                                           name="file" 
                                           id="file" 
                                           class="form-control @error('file') is-invalid @enderror"
                                           accept=".xlsx,.xls,.csv"
                                           required>
                                    <span class="file-name">No file chosen</span>
                                </div>
                                @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-upload"></i> Import Purchase Orders
                                </button>
                            </div>
                        </form>

                        <!-- Error Messages -->
                        @if(session('error'))
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            </div>
                        @endif

                        <!-- Import Failures -->
                        @if(session('import_failures') && count(session('import_failures')) > 0)
                            <div class="alert alert-warning mt-3">
                                <h5><i class="fas fa-exclamation-triangle"></i> Failed Rows</h5>
                                <div class="failures-list">
                                    <ul>
                                        @foreach(session('import_failures') as $failure)
                                            <li>
                                                <strong>Row {{ $failure->row() }}:</strong> 
                                                {{ $failure->errors()[0] ?? 'Validation failed' }}
                                                @if($failure->values())
                                                    <br><small>Values: {{ json_encode($failure->values()) }}</small>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <!-- Example Data -->
                        <div class="example-data mt-3">
                            <h5><i class="fas fa-table"></i> Example Excel Structure</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>purchase_name</th>
                                            <th>purchase_date</th>
                                            <th>company</th>
                                            <th>vendor</th>
                                            <th>receipt_number</th>
                                            <th>receipt_date</th>
                                            <th>receipt_details</th>
                                            <th>remarks</th>
                                            <th>receipt_encoded_by</th>
                                            <th>received_by</th>
                                            <th>pickup_by</th>
                                            <th>bought_by</th>
                                            <th>item_description</th>
                                            <th>unit_price</th>
                                            <th>quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>PO-2024-001</td>
                                            <td>2024-01-15</td>
                                            <td>NEBG</td>
                                            <td>Tech Supplier</td>
                                            <td>RCP-001</td>
                                            <td>2024-01-20</td>
                                            <td>Office equipment</td>
                                            <td>For IT dept</td>
                                            <td>John Doe</td>
                                            <td>Jane Smith</td>
                                            <td>Mike Johnson</td>
                                            <td>Sarah Wilson</td>
                                            <td>Dell Laptop</td>
                                            <td>1500.00</td>
                                            <td>5</td>
                                        </tr>
                                        <tr>
                                            <td>PO-2024-001</td>
                                            <td>2024-01-15</td>
                                            <td>NEBG</td>
                                            <td>Tech Supplier</td>
                                            <td>RCP-001</td>
                                            <td>2024-01-20</td>
                                            <td>Office equipment</td>
                                            <td>For IT dept</td>
                                            <td>John Doe</td>
                                            <td>Jane Smith</td>
                                            <td>Mike Johnson</td>
                                            <td>Sarah Wilson</td>
                                            <td>Samsung Monitor</td>
                                            <td>800.00</td>
                                            <td>3</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.import-container {
    padding: 20px;
}

.import-instructions {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    border-left: 4px solid #007bff;
    margin-bottom: 20px;
}

.import-instructions ul {
    margin-bottom: 0;
    padding-left: 20px;
}

.import-instructions ul li {
    margin-bottom: 5px;
}

.template-download {
    margin: 20px 0;
    padding: 20px;
    background: #e8f4f8;
    border-radius: 5px;
}

.import-form {
    margin-top: 20px;
}

.file-input-wrapper {
    position: relative;
}

.file-input-wrapper input[type="file"] {
    padding: 10px;
    height: auto;
}

.file-input-wrapper input[type="file"]::file-selector-button {
    margin-right: 10px;
    padding: 8px 16px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.file-input-wrapper input[type="file"]::file-selector-button:hover {
    background: #0056b3;
}

.file-name {
    margin-left: 10px;
    color: #6c757d;
}

.form-actions {
    margin-top: 25px;
}

.btn-block {
    width: 100%;
}

.failures-list {
    max-height: 200px;
    overflow-y: auto;
    margin-top: 10px;
}

.failures-list ul {
    list-style: none;
    padding-left: 0;
}

.failures-list li {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.failures-list li:last-child {
    border-bottom: none;
}

.example-data {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
}

.example-data .table {
    margin-bottom: 0;
    font-size: 12px;
}

.example-data .table td {
    padding: 5px 8px;
}

.invalid-feedback {
    display: block;
}

@media (max-width: 768px) {
    .import-container {
        padding: 10px;
    }
    
    .file-input-wrapper input[type="file"] {
        width: 100%;
    }
    
    .example-data .table {
        font-size: 10px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const fileName = document.querySelector('.file-name');

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileName.textContent = this.files[0].name;
        } else {
            fileName.textContent = 'No file chosen';
        }
    });
});
</script>
@endsection