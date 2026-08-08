@extends('layouts.app')

@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<title>Edit Asset</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/edit_asset.css') }}">

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-edit"></i> Edit Asset</h2>
            <p class="page-subtitle">Update asset information</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('assets.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="form-container">
        <form action="{{ route('assets.update', $asset->id) }}" method="POST" class="asset-form" id="editForm">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <!-- Left Column -->
                <div class="form-column">
                    <!-- Asset Tag -->
                    <div class="form-group">
                        <label for="asset_tag">
                            Asset Tag <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="asset_tag" 
                               name="asset_tag" 
                               value="{{ old('asset_tag', $asset->asset_tag) }}"
                               class="form-control @error('asset_tag') is-invalid @enderror"
                               placeholder="Enter asset tag"
                               required>
                        @error('asset_tag')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label for="category">
                            Category <span class="required">*</span>
                        </label>
                        <select id="category" 
                                name="category" 
                                class="form-control @error('category') is-invalid @enderror"
                                required>
                            <option value="">Select Category</option>
                            <option value="Motherboard" {{ old('category', $asset->category) == 'Motherboard' ? 'selected' : '' }}>Motherboard</option>
                            <option value="Power Supply" {{ old('category', $asset->category) == 'Power Supply' ? 'selected' : '' }}>Power Supply</option>
                            <option value="Processor" {{ old('category', $asset->category) == 'Processor' ? 'selected' : '' }}>Processor</option>
                            <option value="RAM" {{ old('category', $asset->category) == 'RAM' ? 'selected' : '' }}>RAM</option>
                            <option value="HDD" {{ old('category', $asset->category) == 'HDD' ? 'selected' : '' }}>HDD</option>
                            <option value="SSD" {{ old('category', $asset->category) == 'SSD' ? 'selected' : '' }}>SSD</option>
                            <option value="Monitor" {{ old('category', $asset->category) == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                            <option value="Keyboard" {{ old('category', $asset->category) == 'Keyboard' ? 'selected' : '' }}>Keyboard</option>
                            <option value="Mouse" {{ old('category', $asset->category) == 'Mouse' ? 'selected' : '' }}>Mouse</option>
                            <option value="AVR" {{ old('category', $asset->category) == 'AVR' ? 'selected' : '' }}>AVR</option>
                            <option value="UPS" {{ old('category', $asset->category) == 'UPS' ? 'selected' : '' }}>UPS</option>
                            <option value="Magic Jack" {{ old('category', $asset->category) == 'Magic Jack' ? 'selected' : '' }}>Magic Jack</option>
                            <option value="Headset" {{ old('category', $asset->category) == 'Headset' ? 'selected' : '' }}>Headset</option>
                            <option value="Dial Pad" {{ old('category', $asset->category) == 'Dial Pad' ? 'selected' : '' }}>Dial Pad</option>
                            <option value="Handset Telephone" {{ old('category', $asset->category) == 'Handset Telephone' ? 'selected' : '' }}>Handset Telephone</option>
                            <option value="Webcam" {{ old('category', $asset->category) == 'Webcam' ? 'selected' : '' }}>Webcam</option>
                            <option value="CCTV" {{ old('category', $asset->category) == 'CCTV' ? 'selected' : '' }}>CCTV</option>
                        </select>
                        @error('category')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Brand -->
                    <div class="form-group">
                        <label for="brand">Brand</label>
                        <input type="text" 
                               id="brand" 
                               name="brand" 
                               value="{{ old('brand', $asset->brand) }}"
                               class="form-control @error('brand') is-invalid @enderror"
                               placeholder="Enter brand name">
                        @error('brand')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Provider -->
                    <div class="form-group">
                        <label for="provider">Provider</label>
                        <input type="text" 
                               id="provider" 
                               name="provider" 
                               value="{{ old('provider', $asset->provider) }}"
                               class="form-control @error('provider') is-invalid @enderror"
                               placeholder="Enter provider/supplier name">
                        @error('provider')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                  <!-- Status -->
                    <div class="form-group">
                        <label for="status">
                            Status
                        </label>

                        <select id="status"
                                name="status"
                                class="form-control @error('status') is-invalid @enderror">

                            <option value="">Select Status</option>

                            <optgroup label="Good Condition">
                                <option value="Good Condition In-Use"
                                    {{ old('status', $asset->status) == 'Good Condition In-Use' ? 'selected' : '' }}>
                                    ✅ Good - In Use
                                </option>

                                <option value="Good Condition In-Stock"
                                    {{ old('status', $asset->status) == 'Good Condition In-Stock' ? 'selected' : '' }}>
                                    ✅ Good - In Stock
                                </option>
                            </optgroup>

                            <optgroup label="Issues">
                                <option value="Defective/In-stock"
                                    {{ old('status', $asset->status) == 'Defective/In-stock' ? 'selected' : '' }}>
                                    ⚠️ Defective - In Stock
                                </option>

                                <option value="Defective/Sold"
                                    {{ old('status', $asset->status) == 'Defective/Sold' ? 'selected' : '' }}>
                                    ⚠️ Defective - Sold
                                </option>

                                <option value="Defective/Thrown"
                                    {{ old('status', $asset->status) == 'Defective/Thrown' ? 'selected' : '' }}>
                                    ⚠️ Defective - Thrown
                                </option>

                                <option value="For Repair"
                                    {{ old('status', $asset->status) == 'For Repair' ? 'selected' : '' }}>
                                    🔧 For Repair
                                </option>
                            </optgroup>

                            <optgroup label="Other">
                                <option value="Obsolete/Stock"
                                    {{ old('status', $asset->status) == 'Obsolete/Stock' ? 'selected' : '' }}>
                                    📦 Obsolete - Stock
                                </option>

                                <option value="Sold"
                                    {{ old('status', $asset->status) == 'Sold' ? 'selected' : '' }}>
                                    💰 Sold
                                </option>

                                <option value="Missing"
                                    {{ old('status', $asset->status) == 'Missing' ? 'selected' : '' }}>
                                    ❓ Missing
                                </option>

                                <option value="For Testing"
                                    {{ old('status', $asset->status) == 'For Testing' ? 'selected' : '' }}>
                                    🧪 For Testing
                                </option>

                                <option value="Return to Vendor"
                                    {{ old('status', $asset->status) == 'Return to Vendor' ? 'selected' : '' }}>
                                    🔄 Return to Vendor
                                </option>

                                <option value="Under Warranty"
                                    {{ old('status', $asset->status) == 'Under Warranty' ? 'selected' : '' }}>
                                    🛡️ Under Warranty
                                </option>
                            </optgroup>

                        </select>

                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Company -->
                    <div class="form-group">
                        <label for="company">
                            Company <span class="required">*</span>
                        </label>
                        <select id="company" 
                                name="company" 
                                class="form-control @error('company') is-invalid @enderror"
                                required>
                            <option value="">Select Company</option>
                            <option value="NEBG" {{ old('company', $asset->company) == 'NEBG' ? 'selected' : '' }}>NEBG</option>
                            <option value="FA" {{ old('company', $asset->company) == 'FA' ? 'selected' : '' }}>FA</option>
                        </select>
                        @error('company')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="form-column">
                    <!-- Delivery Date -->
                    <div class="form-group">
                        <label for="delivery_date">Delivery Date</label>
                        <input type="date" 
                               id="delivery_date" 
                               name="delivery_date" 
                               value="{{ old('delivery_date', $asset->delivery_date ? \Carbon\Carbon::parse($asset->delivery_date)->format('Y-m-d') : '') }}"
                               class="form-control @error('delivery_date') is-invalid @enderror">
                        @error('delivery_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Specification -->
                    <div class="form-group">
                        <label for="specification">Specification</label>
                        <textarea id="specification" 
                                  name="specification" 
                                  class="form-control @error('specification') is-invalid @enderror"
                                  rows="4"
                                  placeholder="Enter technical specifications">{{ old('specification', $asset->specification) }}</textarea>
                        @error('specification')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remarks -->
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea id="remarks" 
                                  name="remarks" 
                                  class="form-control @error('remarks') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Enter any additional notes">{{ old('remarks', $asset->remarks) }}</textarea>
                        @error('remarks')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('assets.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i> Update Asset
                </button>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('js/edit_asset.js') }}"></script>
@endsection