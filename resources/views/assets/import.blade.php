@extends('layouts.app')

@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<link rel="stylesheet" href="{{ asset('css/import_asset.css') }}">
<div class="main-content">
    <div class="page-header">
        <div>
            <h2><i class="fas fa-file-import"></i> Import Assets</h2>
            <p class="page-subtitle">Import assets from Excel or CSV file</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('assets.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Assets
            </a>
        </div>
    </div>

    <div class="import-container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">
                        <!-- Instructions -->
                        <div class="import-instructions">
                            <h4><i class="fas fa-info-circle"></i> Instructions</h4>
                            <ul>
                                <li>Supported formats: <strong>.xlsx, .xls, .csv</strong></li>
                                <li>Maximum file size: <strong>5MB</strong></li>
                                <li><strong>Required columns:</strong> asset_tag, category</li>
                                <li><strong>Company options:</strong> NEBG, FA</li>
                                <li>Download the template below for the correct format</li>
                            </ul>
                        </div>

                        <!-- Template Download -->
                        <div class="template-download text-center">
                            <a href="{{ route('assets.template') }}" class="btn btn-info btn-lg">
                                <i class="fas fa-download"></i> Download Template
                            </a>
                        </div>

                        <!-- Import Form -->
                        <form action="{{ route('assets.import') }}" method="POST" enctype="multipart/form-data" class="import-form">
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
                                    <i class="fas fa-upload"></i> Import Assets
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

                        <!-- Column Mapping Reference -->
                        <div class="column-mapping mt-3">
                            <h5><i class="fas fa-columns"></i> Column Mapping</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Excel Column</th>
                                            <th>Database Field</th>
                                            <th>Required</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Asset Tag</strong></td>
                                            <td>asset_tag</td>
                                            <td><span class="badge badge-danger">Required</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Company</strong></td>
                                            <td>company</td>
                                            <td><span class="badge badge-success">NEBG/FA</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Delivery Date</strong></td>
                                            <td>delivery_date</td>
                                            <td><span class="badge badge-info">Optional</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Category</strong></td>
                                            <td>category</td>
                                            <td><span class="badge badge-danger">Required</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Brand</strong></td>
                                            <td>brand</td>
                                            <td><span class="badge badge-info">Optional</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Provider</strong></td>
                                            <td>provider</td>
                                            <td><span class="badge badge-info">Optional</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status</strong></td>
                                            <td>status</td>
                                            <td><span class="badge badge-info">Optional</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Specification</strong></td>
                                            <td>specification</td>
                                            <td><span class="badge badge-info">Optional</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Remarks</strong></td>
                                            <td>remarks</td>
                                            <td><span class="badge badge-info">Optional</span></td>
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