@extends('layouts.app')

@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<title>Add New PC Asset</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/create_user.css') }}">

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-desktop"></i> Add New PC Asset</h2>
            <p class="page-subtitle">Register a new computer asset with complete specifications</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('user-monitoring.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <form action="{{ route('user-monitoring.store') }}" method="POST" class="pc-form" id="pcForm">
            @csrf

            <!-- PC Information Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i> PC Information
                </h3>
                <div class="form-grid">
                    <!-- PC Name -->
                    <div class="form-group">
                        <label for="pc_name">
                            PC Name <span class="required">*</span>
                        </label>
                        <input type="text" 
                               id="pc_name" 
                               name="pc_name" 
                               value="{{ old('pc_name') }}"
                               class="form-control @error('pc_name') is-invalid @enderror"
                               placeholder="Enter PC name"
                               required>
                        @error('pc_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Serial Number (manual input) -->
                    <div class="form-group">
                        <label for="serial_number">
                            Serial/Receipt Number
                        </label>
                        <input type="text" 
                               id="serial_number" 
                               name="serial_number" 
                               value="{{ old('serial_number') }}"
                               class="form-control @error('serial_number') is-invalid @enderror"
                               placeholder="Enter serial or receipt number (optional)">
                        @error('serial_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- PC User (Select2 Dropdown) -->
                    <div class="form-group">
                        <label for="pc_user">
                            PC User <span class="required">*</span>
                        </label>
                        <select id="pc_user" 
                                name="idno" 
                                class="form-control select2 @error('idno') is-invalid @enderror"
                                required>
                            <option value="">Search for employee...</option>
                            @foreach($employees as $employee)
                                <option
                                    value="{{ $employee->idno }}"
                                    data-department="{{ $employee->department }}"
                                    data-designation="{{ $employee->designation }}"
                                    data-company="{{ $employee->company }}"
                                    data-work_area="{{ $employee->location }}"
                                    data-location="{{ $employee->work_area }}"
                                    {{ old('idno') == $employee->idno ? 'selected' : '' }}
                                >
                                    {{ $employee->lastname }},
                                    {{ $employee->firstname }}
                                    ({{ $employee->idno }})
                                </option>
                            @endforeach
                        </select>
                        @error('idno')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div class="form-group">
                        <label for="department">Department</label>
                        <input type="text" 
                               id="department" 
                               name="department" 
                               value="{{ old('department') }}"
                               class="form-control @error('department') is-invalid @enderror"
                               placeholder="Department"
                               readonly>
                        @error('department')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Job Title -->
                    <div class="form-group">
                        <label for="job_title">Job Title</label>
                        <input type="text" 
                               id="job_title" 
                               name="job_title" 
                               value="{{ old('job_title') }}"
                               class="form-control @error('job_title') is-invalid @enderror"
                               placeholder="Job Title"
                               readonly>
                        @error('job_title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Set Up -->
                    <div class="form-group">
                        <label for="location">Set Up</label>
                        <input type="text" 
                               id="location" 
                               name="location" 
                               value="{{ old('location') }}"
                               class="form-control @error('location') is-invalid @enderror"
                               placeholder="Location"
                               readonly>
                        @error('location')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="form-group">
                        <label for="set_up">Location</label>
                        <input type="text" 
                               id="set_up" 
                               name="set_up" 
                               value="{{ old('set_up') }}"
                               class="form-control @error('set_up') is-invalid @enderror"
                               placeholder="Set up location"
                               readonly>
                        @error('set_up')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" 
                               id="address" 
                               name="address" 
                               value="{{ old('address') }}"
                               class="form-control @error('address') is-invalid @enderror"
                               placeholder="Address"
                               readonly>
                        @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Company -->
                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" 
                               id="company" 
                               name="company" 
                               value="{{ old('company') }}"
                               class="form-control @error('company') is-invalid @enderror"
                               placeholder="Company"
                               readonly>
                        @error('company')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Delivery Date (manual input) -->
                    <div class="form-group">
                        <label for="delivery_date">Delivery Date</label>
                        <input type="date" 
                               id="delivery_date" 
                               name="delivery_date" 
                               value="{{ old('delivery_date', date('Y-m-d')) }}"
                               class="form-control @error('delivery_date') is-invalid @enderror">
                        @error('delivery_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- PC Cost (manual input) -->
                    <div class="form-group">
                        <label for="pc_cost">PC Cost</label>
                        <div class="input-with-icon">
                            <span class="input-icon">₱</span>
                            <input type="number" 
                                   id="pc_cost" 
                                   name="pc_cost" 
                                   value="{{ old('pc_cost', 0) }}" 
                                   class="form-control @error('pc_cost') is-invalid @enderror" 
                                   step="0.01" 
                                   min="0" 
                                   placeholder="0.00">
                        </div>
                        @error('pc_cost')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Store -->
                    <div class="form-group">
                        <label for="store">Store</label>
                        <input type="text" 
                               id="store" 
                               name="store" 
                               value="{{ old('store') }}"
                               class="form-control @error('store') is-invalid @enderror"
                               placeholder="Store or supplier name">
                        @error('store')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

         <!-- Hardware Components Section -->
<div class="form-section">
    <h3 class="section-title">
        <i class="fas fa-microchip"></i> Hardware Components
    </h3>
    <p class="section-description">Select assets from available stock. Only assets with status "Good Condition In-Stock" or "Good Condition In-Use" are shown.</p>
    
    @php
        $hardwareCategories = [
            'motherboard' => 'Motherboard',
            'processor' => 'Processor',
            'hdd' => 'HDD',
            'ssd' => 'SSD',
            'ram' => 'RAM',
            'psu' => 'Power Supply',
            'cpuf' => 'CPU Fan',
            'monitor' => 'Monitor',
            'keyboard' => 'Keyboard',
            'mouse' => 'Mouse',
            'avr' => 'AVR',
            'ups' => 'UPS',
            'headset' => 'Headset',
            'webcam' => 'Webcam',
            'dialpad' => 'Dial Pad',
            'magic_jack' => 'Magic Jack',
            'handset_telephone' => 'Handset Telephone',
            'cctv' => 'CCTV',
            'printer' => 'Printer',
            'router' => 'Router',
            'switch_ports' => 'Switch 8 Ports',
            'usb_hub' => 'USB Hub',
            'phone_splitter' => 'Phone Splitter',
            'pc_set' => 'PC Set',
            'blower' => 'Blower',
            'flash_drive' => 'Flash Drive',
            'battery' => 'Battery',
            'walkie_talkie' => 'Walkie Talkie',
            'company_cellphone' => 'Company Cellphone',
            'case' => 'Case',
        ];
        
    @endphp

    <div class="hardware-grid">
        @foreach($hardwareCategories as $field => $label)
            <div class="form-group hardware-group">
                <label for="{{ $field }}">{{ $label }}</label>
                <div class="asset-select-wrapper">
                    <select id="{{ $field }}" 
                            name="{{ $field }}" 
                            class="form-control asset-select @error($field) is-invalid @enderror">
                        <option value="">Select {{ $label }}...</option>
                        @if(isset($assetsByCategory[$label]) && $assetsByCategory[$label]->count() > 0)
                            @foreach($assetsByCategory[$label] as $asset)
                                <option value="{{ $asset->asset_tag }}" 
                                    {{ old($field) == $asset->asset_tag ? 'selected' : '' }}
                                    data-brand="{{ $asset->brand }}"
                                    data-spec="{{ $asset->specification }}"
                                    data-status="{{ $asset->status }}">
                                    {{ $asset->asset_tag }}
                                    @if($asset->specification) - {{ Str::limit($asset->specification, 30) }} @endif
                                    <span class="asset-status-label">({{ $asset->status }})</span>
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled class="no-assets-option">No {{ $label }} assets available</option>
                        @endif
            <option value="manual">+ Manually Enter</option>
                    </select>
        <div class="manual-input-wrapper" id="manual_{{ $field }}" style="display: none;">
            <input type="text" 
                   id="manual_text_{{ $field }}" 
                   class="form-control manual-input" 
                   placeholder="Enter {{ $label }} manually...">
        </div>
                    <div class="asset-preview" id="preview_{{ $field }}" style="display: none;">
                        <span class="asset-info">
                            <i class="fas fa-info-circle"></i>
                            <span id="preview_text_{{ $field }}"></span>
                        </span>
                    </div>
                    @if(!isset($assetsByCategory[$label]) || $assetsByCategory[$label]->count() == 0)
                        <div class="asset-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                No {{ $label }} assets in stock. You can manually enter one below.
                        </div>
                    @endif
                </div>
                @error($field)
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        @endforeach
    </div>
</div>

            <!-- Network Information Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-network-wired"></i> Network Information
                </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="ip_address">IP Address</label>
                        <input type="text" id="ip_address" name="ip_address" value="{{ old('ip_address') }}" class="form-control" placeholder="IP Address">
                    </div>

                    <div class="form-group">
                        <label for="vpn">VPN</label>
                        <input type="text" id="vpn" name="vpn" value="{{ old('vpn') }}" class="form-control" placeholder="VPN details">
                    </div>
                </div>
            </div>

            <!-- Software Information Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-laptop-code"></i> Software Information
                </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="operating_system">Operating System</label>
                        <input type="text" id="operating_system" name="operating_system" value="{{ old('operating_system') }}" class="form-control" placeholder="Windows 10/11 Pro">
                    </div>

                    <div class="form-group">
                        <label for="product_key">Product Key</label>
                        <input type="text" id="product_key" name="product_key" value="{{ old('product_key') }}" class="form-control" placeholder="Windows Product Key">
                    </div>

                    <div class="form-group">
                        <label for="microsoft_office">Microsoft Office</label>
                        <input type="text" id="microsoft_office" name="microsoft_office" value="{{ old('microsoft_office') }}" class="form-control" placeholder="Office version">
                    </div>

                    <div class="form-group">
                        <label for="office_serial_key">Serial Key (Office)</label>
                        <input type="text" id="office_serial_key" name="office_serial_key" value="{{ old('office_serial_key') }}" class="form-control" placeholder="Office Serial Key">
                    </div>

                    <div class="form-group">
                        <label for="microsoft_account">Microsoft Account</label>
                        <input type="text" id="microsoft_account" name="microsoft_account" value="{{ old('microsoft_account') }}" class="form-control" placeholder="Microsoft Account email">
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('user-monitoring.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i> Save PC Asset
                </button>
            </div>
        </form>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    window.employeeData = @json($employees);
</script>

<script src="{{ asset('js/create_user.js') }}"></script>
@endsection