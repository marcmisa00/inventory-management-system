@extends('layouts.app')

@section('content')

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<title>Create User</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/users_create.css') }}">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<div class="main-content">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2>
                <i class="fas fa-user-plus"></i>
                Add User
            </h2>
            <p class="page-subtitle">
                Create a new inventory account
            </p>
        </div>
        <div class="header-actions">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>
    </div>
    <div class="form-card">
        @if($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <!-- Employee -->
            <div class="form-group">
                <label>
                    Employee
                </label>
                <select
                    name="idno"
                    id="employee"
                    required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->idno }}">
                            {{ $employee->lastname }},
                            {{ $employee->firstname }}
                            {{ $employee->middlename }}
                            ({{ $employee->idno }})
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Username -->
            <div class="form-group">
                <label>
                    Username
                </label>
                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    class="form-control"
                    required>
            </div>
            <!-- Password -->
            <div class="form-group">
                <label>
                    Password
                </label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    required>
            </div>
            <!-- Confirm Password -->
            <div class="form-group">
                <label>
                    Confirm Password
                </label>
                <input
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    required>
            </div>
            <!-- Role -->
            <div class="form-group">
                <label>
                    Role
                </label>
                <select
                    name="role"
                    class="form-control"
                    required>
                    <option value="">Select Role</option>
                    <option value="Inventory Admin">
                        Inventory Admin
                    </option>
                    <option value="IT Staff">
                        IT Staff
                    </option>
                    <option value="Viewer">
                        Viewer
                    </option>
                </select>
            </div>
            <!-- Status -->
            <div class="form-check">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="is_active"
                    checked>
                <label>
                    Active Account
                </label>
            </div>
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a
                    href="{{ route('users.index') }}"
                    class="btn btn-secondary">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Save User
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
new TomSelect("#employee",{
    create:false,
    sortField:{
        field:"text",
        direction:"asc"
    },
    placeholder:"Search employee..."
});
</script>

@endsection