@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/users_edit.css') }}">
<div class="main-content">
    <div class="page-header">
        <div>
            <h2>
                <i class="fas fa-user-edit"></i>
                Edit User
            </h2>
            <p class="page-subtitle">
                Update inventory user account
            </p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
    <div class="form-card">
        <form action="{{ route('users.update',$user->id) }}"
              method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Employee</label>
                <input type="text"
                       class="form-control"
                       value="{{ $user->employeeProfile->lastname }}, {{ $user->employeeProfile->firstname }} {{ $user->employeeProfile->middlename }}"
                       readonly>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text"
                       name="username"
                       class="form-control"
                       value="{{ old('username',$user->username) }}"
                       required>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <small class="text-muted">
                    Leave blank to keep current password.
                </small>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password"
                    name="password_confirmation"
                    class="form-control @error('password') is-invalid @enderror">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role"
                        class="form-control">
                    <option
                        value="Inventory Admin"
                        {{ $user->role=='Inventory Admin' ? 'selected':'' }}>
                        Inventory Admin
                    </option>

                    <option
                        value="Inventory Staff"
                        {{ $user->role=='Inventory Staff' ? 'selected':'' }}>
                        Inventory Staff
                    </option>

                    <option
                        value="Viewer"
                        {{ $user->role=='Viewer' ? 'selected':'' }}>
                        Viewer
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="is_active"
                        class="form-control">
                    <option value="1"
                        {{ $user->is_active ? 'selected':'' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ !$user->is_active ? 'selected':'' }}>
                        Inactive
                    </option>
                </select>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

@endsection