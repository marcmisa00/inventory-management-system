@extends('layouts.app')

@section('content')

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Management</title>
<link rel="stylesheet" href="{{ asset('css/users_index.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2>
                <i class="fas fa-users"></i>
                User Management
            </h2>
            <p class="page-subtitle">
                Manage inventory user accounts and permissions
            </p>
        </div>

        <div class="header-actions">
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i>
                Add User
            </a>
        </div>
    </div>
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif
    <!-- Search -->
    <div class="search-section">
        <form method="GET" action="{{ route('users.index') }}">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Search username or employee..."
                    value="{{ request('search') }}"
                >
                <button class="btn btn-search">
                    <i class="fas fa-search"></i>
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('users.index') }}" class="btn btn-clear">
                        <i class="fas fa-times"></i>
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
    <!-- Table -->
    <div class="table-container">
        <div class="table-header-info">
            <span class="record-count">
                Total Users :
                <strong>{{ $users->count() }}</strong>
            </span>
        </div>
        <table class="assets-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Employee</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th width="180">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        <strong>
                            {{ $user->username }}
                        </strong>
                    </td>
                    <td>
                        @if($user->employeeProfile)
                            {{ $user->employeeProfile->lastname }},
                            {{ $user->employeeProfile->firstname }}
                        @else
                            <span class="text-danger">
                                Employee not found
                            </span>
                        @endif
                    </td>
                    <td>
                        @php
                            $roleClass = match($user->role){
                                'Inventory Admin' => 'role-admin',
                                'IT Staff' => 'role-it',
                                default => 'role-viewer'
                            };
                        @endphp
                        <span class="role-badge {{ $roleClass }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="status-active">
                                <i class="fas fa-circle"></i>
                                Active
                            </span>
                        @else
                            <span class="status-inactive">
                                <i class="fas fa-circle"></i>
                                Inactive
                            </span>
                        @endif
                    </td>

                    <td>
                        <div class="action-buttons">
                            <a
                                href="{{ route('users.edit',$user->id) }}"
                                class="btn-action btn-edit"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button
                                class="btn-action btn-warning resetBtn"
                                data-id="{{ $user->id }}"
                                title="Reset Password">
                                <i class="fas fa-key"></i>
                            </button>
                            @if(auth()->id() != $user->id)
                            <button
                                class="btn-action btn-delete deleteBtn"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->username }}">
                                <i class="fas fa-user-slash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <br>
                        No users found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Deactivate Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5>
                    <i class="fas fa-user-slash"></i>
                    Deactivate User
                </h5>
            </div>
            <div class="modal-body">
                Are you sure you want to deactivate
                <strong id="deleteUserName"></strong> ?
            </div>
            <div class="modal-footer">
                <button
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Cancel
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">
                        Deactivate
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.deleteBtn').forEach(btn=>{
    btn.addEventListener('click',function(){
        document.getElementById('deleteUserName').innerHTML=this.dataset.name;
        document.getElementById('deleteForm').action='/users/'+this.dataset.id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    });
});
</script>

@endsection