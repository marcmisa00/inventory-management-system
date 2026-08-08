@extends('layouts.app')

@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<title>User Monitoring</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/index_user.css') }}">

<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-desktop"></i> User Monitoring</h2>
            <p class="page-subtitle">Manage and track all computer assets</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('user-monitoring.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Add Record
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                <i class="fas fa-desktop"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Records</span>
                <span class="stat-value">{{ $totalRecords }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #dcfce7; color: #166534;">
                <i class="fas fa-user"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Assigned Users</span>
                <span class="stat-value">{{ $assignedCount }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #fef3c7; color: #92400e;">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Departments</span>
                <span class="stat-value">{{ $departmentCount }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #fce7f3; color: #9d174d;">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Value</span>
                <span class="stat-value">₱{{ number_format($totalCost, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="search-section">
        <form action="{{ route('user-monitoring.index') }}" method="GET" class="search-form">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search by PC name, serial, user, IP address..." 
                    value="{{ request('search') }}"
                    class="search-input"
                >
                
                <!-- Department Filter -->
                <select name="department" class="filter-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>

                <!-- Company Filter -->
                <select name="company" class="filter-select">
                    <option value="">All Companies</option>
                    <option value="NEBG" {{ request('company') == 'NEBG' ? 'selected' : '' }}>NEBG</option>
                    <option value="FA" {{ request('company') == 'FA' ? 'selected' : '' }}>FA</option>
                </select>

                <button type="submit" class="btn btn-search">
                    <i class="fas fa-search"></i> Search
                </button>
                
                @if(request('search') || request('department') || request('company'))
                    <a href="{{ route('user-monitoring.index') }}" class="btn btn-clear">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- User Monitoring Table -->
    <div class="table-container">
        <div class="table-header-info">
            <span class="record-count">Showing {{ $userMonitoring->firstItem() ?? 0 }} to {{ $userMonitoring->lastItem() ?? 0 }} of {{ $userMonitoring->total() }} records</span>
            <span class="sort-info">
                <i class="fas fa-sort-amount-down"></i> Latest first
            </span>
        </div>

        @if($userMonitoring->count() > 0)
            <table class="user-monitoring-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>PC Name</th>
                        <th>Serial #</th>
                        <th>User</th>
                        <th>Department</th>
                        <th>Company</th>
                        <th>IP Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userMonitoring as $record)
                        <tr>
                            <td>{{ $loop->iteration + ($userMonitoring->currentPage() - 1) * $userMonitoring->perPage() }}</td>
                            <td>
                                <span class="pc-name">{{ $record->pc_name }}</span>
                            </td>
                            <td>
                                <span class="serial-number">{{ $record->serial_number }}</span>
                            </td>
                            <td>
                                @if($record->idno)
                                    <span class="user-name">
                                        <i class="fas fa-user"></i>
                                        {{ $record->full_name }}
                                    </span>
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="department-badge">{{ $record->department ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="company-badge company-{{ strtolower($record->company ?? 'n/a') }}">
                                    {{ $record->company ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @if($record->ip_address)
                                    <span class="ip-address">{{ $record->ip_address }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('user-monitoring.show', $record->id) }}" class="btn-action btn-view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('user-monitoring.edit', $record->id) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('user-monitoring.destroy', $record->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this record?')">
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
                {{ $userMonitoring->appends([
                    'search' => request('search'),
                    'department' => request('department'),
                    'company' => request('company')
                ])->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-desktop"></i>
                <p>No records found.</p>
                @if(request('search') || request('department') || request('company'))
                    <p class="empty-subtext">Try adjusting your search or filter criteria.</p>
                @else
                    <a href="{{ route('user-monitoring.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Add your first record
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

@endsection