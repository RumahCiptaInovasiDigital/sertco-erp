@extends('layouts.master')
@section('title', $title)
@section('PageTitle', 'Jadwal Kerja')

@section('head')
    <style>
        /* Page Header */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .page-header p {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .btn-assign-shift {
            background: #000;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 0.625rem 1.25rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-assign-shift:hover {
            background: #1f2937;
            color: white;
        }

        /* Shift Cards */
        .shift-cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .shift-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.2s;
            cursor: pointer;
        }

        .shift-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .shift-card-header {
            margin-bottom: 0.5rem;
        }

        .shift-name {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .shift-time {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .shift-count {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-top: 1rem;
        }

        /* Search Box */
        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        /* Master Table */
        .master-table-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .master-table-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1.5rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .master-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .master-table thead th {
            background: #f9fafb;
            color: #6b7280;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .master-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            font-size: 0.875rem;
        }

        .master-table tbody tr:hover {
            background: #f9fafb;
        }

        .user-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .dept-badge, .shift-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: #f3f4f6;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #374151;
        }

        .shift-badge {
            background: #000;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-edit {
            color: #3b82f6;
        }

        .btn-edit:hover {
            background: #eff6ff;
        }

        .btn-delete {
            color: #ef4444;
        }

        .btn-delete:hover {
            background: #fef2f2;
        }
    </style>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('v1.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">@yield('PageTitle')</li>
    </ol>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Jadwal Kerja</h1>
            <p>Master jadwal - assign shift ke karyawan</p>
        </div>
        <button class="btn-assign-shift" id="assign-shift-btn">
            <i class="fas fa-plus"></i>
            Assign Shift
        </button>
    </div>

    <!-- Shift Cards -->
    <div class="shift-cards-container">
        @foreach($shifts as $shift)
            <div class="shift-card" data-shift-id="{{ $shift->id }}">
                <div class="shift-card-header">
                    <div class="shift-name">{{ $shift->nama_shift }}</div>
                    <div class="shift-time">{{ \Carbon\Carbon::parse($shift->jam_masuk_min)->format('H:i') }}-{{ \Carbon\Carbon::parse($shift->jam_pulang_max)->format('H:i') }}</div>
                </div>
                <div class="shift-count">
                    <span id="count-{{ $shift->id }}">{{ $shift->karyawan_count ?? 0 }}</span> Karyawan
                </div>
            </div>
        @endforeach
    </div>

    <!-- Search Box -->
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="search-input" placeholder="Cari karyawan atau departemen...">
    </div>

    <!-- Master Jadwal Table -->
    <div class="master-table-card">
        <h3 class="master-table-title">Master Jadwal Kerja</h3>

        <div class="table-responsive">
            <table class="master-table" id="jadwal-kerja-table">
                <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Departemen</th>
                    <th>Shift</th>
                    <th>Jam Kerja</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <!-- Data populated by DataTables -->
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            // Initialize DataTable
            var table = $("#jadwal-kerja-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('presensi.master.jadwal-kerja.get') }}",
                columns: [
                    {
                        data: 'karyawan',
                        name: 'karyawan',
                        render: function(data, type, row) {
                            return `
                                <div class="user-cell">
                                    <img src="${data.avatar || '/img/default-avatar.png'}" class="user-avatar" alt="${data.name}">
                                    <span>${data.name}</span>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'jabatan',
                        name: 'jabatan',
                        render: function(data) {
                            return `<span class="dept-badge">${data}</span>`;
                        }
                    },
                    {
                        data: 'shift',
                        name: 'shift',
                        render: function(data) {
                            return `<span class="shift-badge">${data}</span>`;
                        }
                    },
                    { data: 'jam_kerja', name: 'jam_kerja' },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <div class="action-buttons">
                                    <button class="btn-icon btn-edit" data-id="${data}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon btn-delete delete-btn" data-id="${data}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                searching: true,
                dom: 'rtip',
                language: {
                    paginate: {
                        previous: "‹",
                        next: "›"
                    }
                }
            });

            // Custom search
            $('#search-input').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Delete handler
            $('#jadwal-kerja-table').on('click', '.delete-btn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Anda Yakin?',
                    text: "Data jadwal ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('presensi.master.jadwal-kerja.delete', ['id' => ':id']) }}".replace(':id', id),
                            type: 'DELETE',
                            data: { _token: "{{ csrf_token() }}" },
                            success: function(response) {
                                Swal.fire('Dihapus!', response.success, 'success');
                                table.ajax.reload();
                                updateShiftCounts();
                            },
                            error: function() {
                                Swal.fire('Gagal!', 'Terjadi kesalahan', 'error');
                            }
                        });
                    }
                });
            });

            // Update shift counts
            function updateShiftCounts() {
                $.ajax({
                    url: "{{ route('presensi.master.jadwal-kerja.shift-counts') }}",
                    type: 'GET',
                    success: function(data) {
                        $.each(data, function(shiftId, count) {
                            $('#count-' + shiftId).text(count);
                        });
                    }
                });
            }

            updateShiftCounts();
        });
    </script>
@endsection
