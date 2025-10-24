@extends('layouts.master')
@section('title', 'Dashboard')
@section('PageTitle', 'Project Sheet Execution Dashboard')

@section('head')
<!-- Chart.js -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item active">Dashboard</li>
</ol>
@endsection

@section('content')
<div class="row">
    {{-- Stat boxes --}}
    <div class="col-lg col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>120</h3>
                <p>Total Projects</p>
            </div>
            <div class="icon"><i class="fas fa-tasks"></i></div>
        </div>
    </div>

    <div class="col-lg col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>25</h3>
                <p>Draft</p>
            </div>
            <div class="icon"><i class="fas fa-edit"></i></div>
        </div>
    </div>

    <div class="col-lg col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>60</h3>
                <p>In Progress</p>
            </div>
            <div class="icon"><i class="fas fa-spinner fa-spin"></i></div>
        </div>
    </div>

    <div class="col-lg col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>30</h3>
                <p>Completed</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>

    <div class="col-lg col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>5</h3>
                <p>Delayed</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Line Chart --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-light">
                <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i>Project Created Per Day</h3>
            </div>
            <div class="card-body">
                <canvas id="projectChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Pie Chart --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-light">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i>Project by Department</h3>
            </div>
            <div class="card-body">
                <canvas id="deptChart" style="height: 300px;"></canvas>
                <div class="text-center mt-2">
                    <span class="badge badge-primary">Marketing</span>
                    <span class="badge badge-success">Production</span>
                    <span class="badge badge-warning">T&O</span>
                    <span class="badge badge-info">Finance</span>
                    <span class="badge badge-danger">HRGA IT</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Monitoring Table --}}
<div class="card">
    <div class="card-header bg-light">
        <h3 class="card-title"><i class="fas fa-eye mr-2"></i>Project Monitoring</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover table-striped">
            <thead class="thead-light">
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Project Name</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Department</th>
                    <th>Last Update</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1.</td>
                    <td>Punch n Dies Revamp</td>
                    <td><span class="badge badge-info">In Progress</span></td>
                    <td>
                        <div class="progress progress-xs">
                            <div class="progress-bar bg-info" style="width: 65%"></div>
                        </div>
                        <small>65%</small>
                    </td>
                    <td>Engineering</td>
                    <td>2025-10-19 09:12</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>System Calibration</td>
                    <td><span class="badge badge-success">Completed</span></td>
                    <td>
                        <div class="progress progress-xs">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                        <small>100%</small>
                    </td>
                    <td>Production</td>
                    <td>2025-10-18 16:40</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Facility Upgrade</td>
                    <td><span class="badge badge-warning">At Risk</span></td>
                    <td>
                        <div class="progress progress-xs">
                            <div class="progress-bar bg-warning" style="width: 45%"></div>
                        </div>
                        <small>45%</small>
                    </td>
                    <td>T&O</td>
                    <td>2025-10-17 08:45</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Quality Review</td>
                    <td><span class="badge badge-danger">Delayed</span></td>
                    <td>
                        <div class="progress progress-xs">
                            <div class="progress-bar bg-danger" style="width: 30%"></div>
                        </div>
                        <small>30%</small>
                    </td>
                    <td>HRGA IT</td>
                    <td>2025-10-16 10:20</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Material Cost Control</td>
                    <td><span class="badge badge-primary">Draft</span></td>
                    <td>
                        <div class="progress progress-xs">
                            <div class="progress-bar bg-primary" style="width: 10%"></div>
                        </div>
                        <small>10%</small>
                    </td>
                    <td>Finance</td>
                    <td>2025-10-15 14:33</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    // Line Chart
    const ctx = document.getElementById('projectChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Oct 13', 'Oct 14', 'Oct 15', 'Oct 16', 'Oct 17', 'Oct 18', 'Oct 19'],
            datasets: [{
                label: 'Projects Created',
                data: [3, 5, 2, 6, 4, 8, 5],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                borderWidth: 2,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#007bff',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            legend: { display: false },
            scales: {
                yAxes: [{
                    ticks: { beginAtZero: true, stepSize: 2 }
                }]
            }
        }
    });

    // Pie Chart
    const deptCtx = document.getElementById('deptChart').getContext('2d');
    new Chart(deptCtx, {
        type: 'doughnut',
        data: {
            labels: ['Marketing', 'Production', 'T&O', 'Finance', 'HRGA IT'],
            datasets: [{
                data: [15, 40, 25, 10, 8],
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#17a2b8', '#dc3545'],
            }]
        },
        options: {
            responsive: true,
            legend: {
                position: 'bottom'
            }
        }
    });
});
</script>
@endsection
