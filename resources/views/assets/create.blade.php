@extends('layouts.app')


@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<title>Add Asset · ERP System</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/create.css') }}">

<div class="erp-container">
    <!-- Page Header -->
    <div class="erp-page-header">
        <div class="header-left">
            <div class="header-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div>
                <h1 class="header-title">Create New Asset</h1>
                <p class="header-subtitle">Add a new asset to the inventory system</p>
            </div>
        </div>
        <div class="header-right">
            <div class="header-badge">
                <i class="fas fa-file-alt"></i>
                <span>New Entry</span>
            </div>
            <a href="{{ route('assets.index') }}" class="btn-erp btn-erp-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Status Bar -->
    <div class="erp-status-bar">
        <div class="status-item">
            <i class="fas fa-info-circle"></i>
            <span>All fields marked with <span class="text-danger">*</span> are required</span>
        </div>
        <div class="status-item">
            <i class="fas fa-clock"></i>
            <span id="currentDateTime"></span>
        </div>
    </div>

    <!-- Main Form -->
    <form action="{{ route('assets.store') }}" method="POST" class="erp-form" id="assetForm">
        @csrf

        <!-- Form Sections -->
        <div class="erp-form-grid">
            
            <!-- Left Column -->
            <div class="form-column">
                <!-- Basic Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Basic Information</h3>
                            <p class="section-subtitle">Core asset identification details</p>
                        </div>
                    </div>

                    <div class="section-body">
                        <!-- Company -->
                        <div class="form-group">
                            <label for="company" class="form-label required">
                                <i class="fas fa-building"></i> Company
                            </label>
                            <div class="input-group-erp">
                                <span class="input-group-addon">
                                    <i class="fas fa-company"></i>
                                </span>
                                <select id="company" 
                                        name="company" 
                                        class="form-control-erp @error('company') is-invalid @enderror" 
                                        required>
                                    <option value="">Select Company</option>
                                    <option value="NEBG" {{ old('company') == 'NEBG' ? 'selected' : '' }}>
                                        🏢 NEBG
                                    </option>
                                    <option value="FA" {{ old('company') == 'FA' ? 'selected' : '' }}>
                                        🏭 FA
                                    </option>
                                </select>
                                @error('company')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="form-hint">
                                <i class="fas fa-info-circle"></i> Select the company this asset belongs to
                            </small>
                        </div>

                        <!-- Asset Tag -->
                        <div class="form-group">
                            <label for="asset_tag" class="form-label required">
                                <i class="fas fa-tag"></i> Asset Tag
                            </label>
                            <div class="input-group-erp">
                                <span class="input-group-addon">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input type="text" 
                                       id="asset_tag" 
                                       name="asset_tag" 
                                       class="form-control-erp @error('asset_tag') is-invalid @enderror" 
                                       placeholder="e.g. AST-2026-001" 
                                       value="{{ old('asset_tag') }}"
                                       required>
                                @error('asset_tag')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="form-hint">
                                <i class="fas fa-info-circle"></i> Unique identifier for this asset
                            </small>
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <label for="category" class="form-label required">
                                <i class="fas fa-list-ul"></i> Category
                            </label>
                            <div class="input-group-erp">
                                <span class="input-group-addon">
                                    <i class="fas fa-folder"></i>
                                </span>
                                <select id="category" 
                                        name="category" 
                                        class="form-control-erp @error('category') is-invalid @enderror" 
                                        required>
                                    <option value="">Select Category</option>
                                    <optgroup label="Computer Components">
                                        <option value="Motherboard" {{ old('category') == 'Motherboard' ? 'selected' : '' }}>Motherboard</option>
                                        <option value="Processor" {{ old('category') == 'Processor' ? 'selected' : '' }}>Processor</option>
                                        <option value="RAM" {{ old('category') == 'RAM' ? 'selected' : '' }}>RAM</option>
                                        <option value="HDD" {{ old('category') == 'HDD' ? 'selected' : '' }}>HDD</option>
                                        <option value="SSD" {{ old('category') == 'SSD' ? 'selected' : '' }}>SSD</option>
                                        <option value="Power Supply" {{ old('category') == 'Power Supply' ? 'selected' : '' }}>Power Supply</option>
                                        <option value="CPU Fan" {{ old('category') == 'CPU Fan' ? 'selected' : '' }}>CPU Fan</option>
                                        <option value="Case" {{ old('category') == 'Case' ? 'selected' : '' }}>Case</option>
                                    </optgroup>
                                    <optgroup label="Peripherals">
                                        <option value="Monitor" {{ old('category') == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                                        <option value="Keyboard" {{ old('category') == 'Keyboard' ? 'selected' : '' }}>Keyboard</option>
                                        <option value="Mouse" {{ old('category') == 'Mouse' ? 'selected' : '' }}>Mouse</option>
                                        <option value="Headset" {{ old('category') == 'Headset' ? 'selected' : '' }}>Headset</option>
                                        <option value="Webcam" {{ old('category') == 'Webcam' ? 'selected' : '' }}>Webcam</option>
                                        <option value="Printer" {{ old('category') == 'Printer' ? 'selected' : '' }}>Printer</option>
                                    </optgroup>
                                    <optgroup label="Networking">
                                        <option value="Router" {{ old('category') == 'Router' ? 'selected' : '' }}>Router</option>
                                        <option value="Switch 8 Ports" {{ old('category') == 'Switch 8 Ports' ? 'selected' : '' }}>Switch 8 Ports</option>
                                        <option value="USB Hub" {{ old('category') == 'USB Hub' ? 'selected' : '' }}>USB Hub</option>
                                    </optgroup>
                                    <optgroup label="Telecom">
                                        <option value="Handset Telephone" {{ old('category') == 'Handset Telephone' ? 'selected' : '' }}>Handset Telephone</option>
                                        <option value="Dial Pad" {{ old('category') == 'Dial Pad' ? 'selected' : '' }}>Dial Pad</option>
                                        <option value="Phone Splitter" {{ old('category') == 'Phone Splitter' ? 'selected' : '' }}>Phone Splitter</option>
                                        <option value="Magic Jack" {{ old('category') == 'Magic Jack' ? 'selected' : '' }}>Magic Jack</option>
                                    </optgroup>
                                    <optgroup label="Others">
                                        <option value="PC Set" {{ old('category') == 'PC Set' ? 'selected' : '' }}>PC Set</option>
                                        <option value="CCTV" {{ old('category') == 'CCTV' ? 'selected' : '' }}>CCTV</option>
                                        <option value="AVR" {{ old('category') == 'AVR' ? 'selected' : '' }}>AVR</option>
                                        <option value="UPS" {{ old('category') == 'UPS' ? 'selected' : '' }}>UPS</option>
                                        <option value="Blower" {{ old('category') == 'Blower' ? 'selected' : '' }}>Blower</option>
                                        <option value="Flash Drive" {{ old('category') == 'Flash Drive' ? 'selected' : '' }}>Flash Drive</option>
                                        <option value="Battery" {{ old('category') == 'Battery' ? 'selected' : '' }}>Battery</option>
                                        <option value="Walkie Talkie" {{ old('category') == 'Walkie Talkie' ? 'selected' : '' }}>Walkie Talkie</option>
                                        <option value="Company Cellphone" {{ old('category') == 'Company Cellphone' ? 'selected' : '' }}>Company Cellphone</option>
                                    </optgroup>
                                </select>
                                @error('category')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Brand/Description -->
                        <div class="form-group">
                            <label for="brand" class="form-label">
                                <i class="fas fa-cube"></i> Brand / Model
                            </label>
                            <div class="input-group-erp">
                                <span class="input-group-addon">
                                    <i class="fas fa-trademark"></i>
                                </span>
                                <input type="text" 
                                       id="brand" 
                                       name="brand" 
                                       class="form-control-erp @error('brand') is-invalid @enderror" 
                                       placeholder="e.g. Dell, Kingston, Samsung" 
                                       value="{{ old('brand') }}">
                                @error('brand')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Provider -->
                        <div class="form-group">
                            <label for="provider" class="form-label">
                                <i class="fas fa-building"></i> Provider / Supplier
                            </label>
                            <div class="input-group-erp">
                                <span class="input-group-addon">
                                    <i class="fas fa-store"></i>
                                </span>
                                <input type="text" 
                                       id="provider" 
                                       name="provider" 
                                       class="form-control-erp @error('provider') is-invalid @enderror" 
                                       placeholder="e.g. Computer World" 
                                       value="{{ old('provider') }}">
                                @error('provider')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-toggle-on"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Status & Condition</h3>
                            <p class="section-subtitle">Current state and availability</p>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="form-group">
                            <label for="status" class="form-label required">
                                <i class="fas fa-circle"></i> Status
                            </label>
                            <div class="input-group-erp">
                                <span class="input-group-addon">
                                    <i class="fas fa-flag"></i>
                                </span>
                                <select id="status" 
                                        name="status" 
                                        class="form-control-erp @error('status') is-invalid @enderror" 
                                        required>
                                    <option value="">Select Status</option>
                                    <optgroup label="Good Condition">
                                        <option value="Good Condition In-Use" {{ old('status') == 'Good Condition In-Use' ? 'selected' : '' }}>✅ Good - In Use</option>
                                        <option value="Good Condition In-Stock" {{ old('status') == 'Good Condition In-Stock' ? 'selected' : '' }}>✅ Good - In Stock</option>
                                    </optgroup>
                                    <optgroup label="Issues">
                                        <option value="Defective/In-Stock" {{ old('status') == 'Defective/In-Stock' ? 'selected' : '' }}>⚠️ Defective - In Stock</option>
                                        <option value="Defective/Sold" {{ old('status') == 'Defective/Sold' ? 'selected' : '' }}>⚠️ Defective - Sold</option>
                                        <option value="Defective/Thrown" {{ old('status') == 'Defective/Thrown' ? 'selected' : '' }}>⚠️ Defective - Thrown</option>
                                        <option value="FOR REPAIR" {{ old('status') == 'FOR REPAIR' ? 'selected' : '' }}>🔧 For Repair</option>
                                    </optgroup>
                                    <optgroup label="Other">
                                        <option value="Obsolete/Stock" {{ old('status') == 'Obsolete/Stock' ? 'selected' : '' }}>📦 Obsolete - Stock</option>
                                        <option value="SOLD" {{ old('status') == 'SOLD' ? 'selected' : '' }}>💰 SOLD</option>
                                        <option value="Missing" {{ old('status') == 'Missing' ? 'selected' : '' }}>❓ Missing</option>
                                        <option value="FOR TESTING" {{ old('status') == 'FOR TESTING' ? 'selected' : '' }}>⚙️ FOR TESTING</option>
                                        <option value="RETURN TO VENDOR" {{ old('status') == 'RETURN TO VENDOR' ? 'selected' : '' }}>🔄 Return to Vendor</option>
                                        <option value="Under Warranty" {{ old('status') == 'Under Warranty' ? 'selected' : '' }}>🛡️ Under Warranty</option>
                                    </optgroup>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="form-column">
                <!-- Delivery Information Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Delivery Information</h3>
                            <p class="section-subtitle">Delivery and acquisition details</p>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="form-group">
                            <label for="delivery_date" class="form-label">
                                <i class="fas fa-calendar-alt"></i> Delivery Date
                            </label>
                            <div class="input-group-erp">
                                <span class="input-group-addon">
                                    <i class="fas fa-calendar-day"></i>
                                </span>
                                <input type="date" 
                                       id="delivery_date" 
                                       name="delivery_date" 
                                       class="form-control-erp @error('delivery_date') is-invalid @enderror" 
                                       value="{{ old('delivery_date') }}">
                                @error('delivery_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="form-hint">
                                <i class="fas fa-info-circle"></i> Expected or actual delivery date
                            </small>
                        </div>

                        <!-- Quick Actions -->
                        <div class="quick-actions">
                            <button type="button" class="btn-quick" onclick="setToday()">
                                <i class="fas fa-calendar-check"></i> Today
                            </button>
                            <button type="button" class="btn-quick" onclick="clearDate()">
                                <i class="fas fa-times-circle"></i> Clear
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Specifications Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Technical Specifications</h3>
                            <p class="section-subtitle">Detailed technical information</p>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="form-group">
                            <label for="specification" class="form-label">
                                <i class="fas fa-list-check"></i> Specifications
                            </label>
                            <textarea id="specification" 
                                      name="specification" 
                                      class="form-control-erp textarea-erp @error('specification') is-invalid @enderror" 
                                      placeholder="Describe technical details, capacity, speed, etc.&#10;Example:&#10;- 16GB DDR4 RAM&#10;- 512GB NVMe SSD&#10;- Intel i7-10700K">{{ old('specification') }}</textarea>
                            @error('specification')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-hint">
                                <i class="fas fa-info-circle"></i> Include technical specs like capacity, speed, model numbers
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Remarks Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Additional Information</h3>
                            <p class="section-subtitle">Notes and special instructions</p>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="form-group">
                            <label for="remarks" class="form-label">
                                <i class="fas fa-pen"></i> Remarks
                            </label>
                            <textarea id="remarks" 
                                      name="remarks" 
                                      class="form-control-erp textarea-erp @error('remarks') is-invalid @enderror" 
                                      placeholder="Additional notes, condition, location, warranty info, etc.">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-hint">
                                <i class="fas fa-info-circle"></i> Optional notes for internal reference
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="erp-form-actions">
            <div class="actions-left">
                <div class="form-progress">
                    <span class="progress-text">
                        <i class="fas fa-check-circle text-success"></i> 
                        <span id="requiredCount">0</span>/<span id="totalRequired">4</span> required fields filled
                    </span>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
            <div class="actions-right">
                <button type="reset" class="btn-erp btn-erp-warning">
                    <i class="fas fa-undo-alt"></i> Reset
                </button>
                <a href="{{ route('assets.index') }}" class="btn-erp btn-erp-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn-erp btn-erp-primary">
                    <i class="fas fa-save"></i> Create Asset
                </button>
            </div>
        </div>
    </form>
</div>
<script src="{{ asset('js/create.js') }}"></script>
@endsection
