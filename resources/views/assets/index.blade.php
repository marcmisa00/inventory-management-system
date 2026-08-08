@extends('layouts.app')

@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<title>Asset Inventory</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/index_asset.css') }}">

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-boxes"></i> Asset Inventory</h2>
            <p class="page-subtitle">Manage and track your asset stock</p>
        </div>
        <div class="header-actions">
    <a href="{{ route('assets.import.form') }}" class="btn btn-success">
        <i class="fas fa-file-import"></i> Import
    </a>
    <button id="toggleListBtn" class="btn btn-secondary">
        <i class="fas fa-list"></i> Show List
    </button>
    <a href="{{ route('assets.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Add Asset
    </a>
</div>
    </div>

    <!-- Search Bar -->
    <div class="search-section">
        <form action="{{ route('assets.index') }}" method="GET" class="search-form" id="searchForm">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    name="search" 
                    id="searchInput"
                    placeholder="Search by asset tag, brand, category..." 
                    value="{{ request('search') }}"
                    class="search-input"
                >
                <button type="submit" class="btn btn-search">
                    <i class="fas fa-search"></i> Search
                </button>
                @if(request('search'))
                    <a href="{{ route('assets.index') }}" class="btn btn-clear">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Compact Asset Overview by Category -->
    <div class="overview-container" id="overviewContainer">
        <div class="overview-header">
            <h3><i class="fas fa-th-large"></i> Asset Overview</h3>
            <span class="total-assets">Total: <strong>{{ $totalAssets }}</strong></span>
        </div>

        <div class="category-grid" id="categoryGrid">
            @php
                $statusIcons = [
                    'Good Condition In-Use' => '✅',
                    'Good Condition In-Stock' => '📦',
                    'Defective/In-Stock' => '🛠',
                    'Defective/Sold' => '⚠️',
                    'Defective/Thrown' => '❌',
                    'SOLD' => '💰',
                    'FOR REPAIR' => '🔧',
                    'FOR TESTING' => '⚙️',
                ];
                
                $statusColors = [
                    'Good Condition In-Use' => 'status-good-condition-in-use',
                    'Good Condition In-Stock' => 'status-good-condition-in-stock',
                    'Defective/In-Stock' => 'status-defective-in-stock',
                    'Defective/Sold' => 'status-defective-sold',
                    'Defective/Thrown' => 'status-defective-thrown',
                    'SOLD' => 'status-sold',
                    'FOR REPAIR' => 'status-for-repair',
                    'FOR TESTING' => 'status-for-testing',
                ];
            @endphp

            @forelse($categoryStatusCounts as $category => $statuses)
                <div class="category-card" data-category="{{ $category }}">
                    <div class="category-header">
                        <span class="category-name">{{ $category }}</span>
                        <span class="category-total">{{ array_sum($statuses) }}</span>
                    </div>
                    <div class="category-statuses">
                        @foreach($statuses as $status => $count)
                            @if($count > 0)
                                <div class="status-item {{ $statusColors[$status] ?? 'status-default' }}">
                                    <span class="status-icon">{{ $statusIcons[$status] ?? '•' }}</span>
                                    <span class="status-label">{{ $status }}</span>
                                    <span class="status-count">{{ $count }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="empty-overview">
                    <i class="fas fa-inbox"></i>
                    <p>No assets found</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Assets Table -->
    <div class="table-container" id="tableContainer">
        <div class="table-header-info">
            <span class="record-count">Showing {{ $assets->firstItem() ?? 0 }} to {{ $assets->lastItem() ?? 0 }} of {{ $assets->total() }} assets</span>
            <button id="closeListBtn" class="btn-close-list">
                <i class="fas fa-times"></i> Close List
            </button>
        </div>

        @if($assets->count() > 0)
            <table class="assets-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Asset Tag</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Company</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assets as $asset)
                        <tr data-category="{{ $asset->category }}">
                            <td>{{ $loop->iteration + ($assets->currentPage() - 1) * $assets->perPage() }}</td>
                            <td>
                                <span class="asset-tag">{{ $asset->asset_tag }}</span>
                            </td>
                            <td>
                                <span class="category-badge">{{ $asset->category }}</span>
                            </td>
                            <td>
                                <div class="asset-description">
                                    <span class="brand-name">{{ $asset->brand ?? 'N/A' }}</span>
                                    @if($asset->specification)
                                        <span class="spec-preview">{{ Str::limit($asset->specification, 30) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower(str_replace([' ', '/'],['-','-'], $asset->status)) }}">
                                    <i class="fas fa-circle status-dot"></i>
                                    {{ $asset->status }}
                                </span>
                            </td>
                            <td>
                                <span class="company-badge company-{{ strtolower($asset->company ?? 'N/A') }}">
                                    {{ $asset->company ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                  <button type="button" class="btn-action btn-view viewAssetBtn" data-id="{{ $asset->id }}" title="View"> <i class="fas fa-eye"></i>
                                        </button>
                                    <a href="{{ route('assets.edit', $asset->id) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this asset?')">
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
                {{ $assets->appends(['search' => request('search')])->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>No assets found.</p>
                @if(request('search'))
                    <p class="empty-subtext">Try adjusting your search terms.</p>
                @else
                    <a href="{{ route('assets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Add your first asset
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
<div class="modal fade" id="assetModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-box"></i> Asset Details
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="assetModalBody">

                <div class="text-center p-5">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                </div>
            </div>
            
             
    </div>
</div>
<script src="{{ asset('js/indexAsset.js') }}"></script>
@endsection