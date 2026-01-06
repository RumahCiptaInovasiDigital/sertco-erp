@extends('layouts.master')
@section('title', 'Feedback Management')
@section('PageTitle', 'User Feedback')
@section('head')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Feedback</li>
</ol>
@endsection
@section('content')
<div class="card">
    <div class="card-body table-responsive">
        <table id="dt_data" class="table table-bordered">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Jabatan</th>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Page</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($feedbacks as $fb)
                <tr>
                    <td>{{ $fb->user->fullname ?? 'Guest' }}</td>
                    <td>{{ $fb->user->jabatan ?? '-' }}</td>
                    <td>{{ $fb->typeLabel() }}</td>
                    <td>{{ Str::limit($fb->message, 80) }}</td>
                    <td><small>{{ $fb->page }}</small></td>
                    <td>
                        <span class="badge badge-{{ 
                            $fb->status == 'open' ? 'danger' :
                            ($fb->status == 'in_progress' ? 'warning' : 'success')
                        }}">
                            {{ ucfirst(str_replace('_',' ', $fb->status)) }}
                        </span>
                    </td>
                    <td>
                        <select class="form-control change-status"
                            data-id="{{ $fb->id }}">
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $feedbacks->links() }}
    </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#dt_data').DataTable({
            "paging":   false,
            "info":     false,
            "searching": false,
            "responsive": true,
            "autoWidth": false,
        });
    });

    $('.change-status').on('change', function () {
        $.post({
            url: '/admin/feedback/' + $(this).data('id') + '/status',
            data: {
                _token: '{{ csrf_token() }}',
                status: $(this).val()
            },
            success: () => Swal.fire('Updated', '', 'success')
        });
    });
</script>

@endsection
