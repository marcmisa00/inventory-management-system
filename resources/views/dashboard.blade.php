
@extends('layouts.app')

@section('content')

     <!-- CONTENT -->
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
        <div class="content">
            <div class="card">
                <h4><i class="fas fa-circle-up me-2" style="color:#1d2437;"></i> Welcome back, Admin</h4>
                <p class="text-secondary" style="margin-bottom: 4px;">Today's summary · IT inventory overview</p>

                <div class="demo-card-grid">
                    <div class="card-item">
                        <i class="fas fa-desktop"></i>
                        <h5>124</h5>
                        <span class="text-secondary">Total assets</span>
                    </div>
                    <div class="card-item">
                        <i class="fas fa-arrow-trend-up"></i>
                        <h5>18</h5>
                        <span class="text-secondary">Pending orders</span>
                    </div>
                    <div class="card-item">
                        <i class="fas fa-building"></i>
                        <h5>6</h5>
                        <span class="text-secondary">Departments</span>
                    </div>
                </div>
                <hr class="my-3">
                <p class="text-muted small"><i class="far fa-clock me-1"></i> Last updated: today 14:32</p>
            </div>
        </div>

        <div class="card mt-4">
    <div class="card-header">
        HRIS Employee Test
    </div>

    <div class="card-body">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>Full Name</th>
                    <th>Address</th>
                    <th>Job Title</th>
                    <th>Company</th>
                    <th>Department</th>
                    <th>Work Area</th>
                    <th>Location</th>
                </tr>
            </thead>

            <tbody>
                @forelse($employees as $employee)
                    <tr>
                        <td>{{ $employee->idno }}</td>
                        <td>{{ $employee->lastname }}, {{ $employee->firstname }}</td>
                        <td>{{ $employee->address }}</td>
                        <td>{{ $employee->designation }}</td>
                        <td>{{ $employee->company }}</td>
                        <td>{{ $employee->department }}</td>
                        <td>{{ $employee->location }}</td>
                        <td>{{ $employee->work_area }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No employees found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
@endsection