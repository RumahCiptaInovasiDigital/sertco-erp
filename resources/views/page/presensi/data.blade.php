
@extends('layouts.master')
@section('title', $title)
@section('PageTitle', 'Data Presensi Karyawan')

@section('head')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin="" />

    <style>
        /* Summary Cards - Grid Layout (Compact) */
        .summary-card-grid {
            background: white;
            padding: 12px;
            border-radius: 8px;
            color: white;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 85px;
        }

        .summary-card-grid:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .summary-icon-grid {
            font-size: 1.8rem;
            margin-bottom: 6px;
            opacity: 0.9;
        }

        .summary-number-grid {
            font-size: 1.6rem;
            font-weight: bold;
            line-height: 1;
            margin-bottom: 3px;
        }

        .summary-text-grid {
            font-size: 0.7rem;
            opacity: 0.95;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* Gradient Colors for Summary Cards */
        .bg-gradient-purple {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .bg-gradient-indigo {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
        }

        .bg-gradient-orange {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        }

        .bg-gradient-teal {
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%) !important;
        }

        .bg-gradient-lime {
            background: linear-gradient(135deg, #84cc16 0%, #65a30d 100%) !important;
        }

        /* Filter Button Styling */
        #toggleFilterBtn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 8px 20px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }

        #toggleFilterBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.6);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        #toggleFilterBtn:active {
            transform: translateY(0);
        }

        #toggleFilterBtn i {
            margin-right: 8px;
        }

        /* Apply Filter Button */
        #applyFilterBtn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            font-weight: 600;
            padding: 10px 24px;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.4);
            transition: all 0.3s ease;
        }

        #applyFilterBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(16, 185, 129, 0.6);
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        }

        #applyFilterBtn i {
            margin-right: 8px;
        }

        /* Filter Section Styling */
        #filterSection .alert {
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        #filterSection .form-control {
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        #filterSection .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Map Styles */
        #attendanceMap {
            border-radius: 5px;
            z-index: 1;
            position: relative;
        }

        /* Leaflet Fix */
        .leaflet-container {
            font-family: 'Source Sans Pro', sans-serif;
            font-size: 14px;
        }

        .leaflet-tile-container {
            pointer-events: auto;
        }

        .leaflet-control-zoom {
            border: 2px solid rgba(0,0,0,0.2) !important;
            border-radius: 4px !important;
        }

        .leaflet-marker-icon {
            margin-left: -12px !important;
            margin-top: -41px !important;
        }

        /* Button Location Styles */
        .view-location {
            transition: all 0.3s ease;
            border-width: 2px;
            font-weight: 500;
        }

        .view-location:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .view-location:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Custom Popup Styles */
        .custom-popup .leaflet-popup-content-wrapper {
            border-radius: 8px;
            box-shadow: 0 3px 14px rgba(0,0,0,0.4);
        }

        .custom-popup .leaflet-popup-content {
            margin: 15px;
        }

        .custom-popup hr {
            margin: 8px 0;
            border-top: 1px solid #dee2e6;
        }

        /* Tabs Styling */
        .nav-tabs .nav-link {
            border-radius: 0;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            background-color: #007bff;
            color: white !important;
            border-color: #007bff;
        }

        .nav-tabs .nav-link:hover:not(.active) {
            background-color: #e9ecef;
        }

        /* Table Styling */
        .table thead th {
            vertical-align: middle;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .table tbody td {
            vertical-align: middle;
        }

        /* Responsive Grid */
        @media (max-width: 1200px) {
            .summary-card-grid {
                min-height: 80px;
                padding: 10px;
            }

            .summary-icon-grid {
                font-size: 1.6rem;
            }

            .summary-number-grid {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 768px) {
            .summary-card-grid {
                min-height: 75px;
                padding: 8px;
            }

            .summary-icon-grid {
                font-size: 1.4rem;
                margin-bottom: 5px;
            }

            .summary-number-grid {
                font-size: 1.2rem;
            }

            .summary-text-grid {
                font-size: 0.65rem;
            }
        }

        @media (max-width: 576px) {
            .summary-card-grid {
                min-height: 70px;
                padding: 6px;
            }

            .summary-icon-grid {
                font-size: 1.2rem;
                margin-bottom: 4px;
            }

            .summary-number-grid {
                font-size: 1.1rem;
            }

            .summary-text-grid {
                font-size: 0.6rem;
            }
        }

        /* Info Box Enhancements */
        .info-box {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            border-radius: 0.25rem;
            min-height: 80px;
        }

        .info-box .info-box-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 90px;
        }

        /* Badge Enhancements */
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        /* Card Header Gradient */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
        }
    </style>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">@yield('PageTitle')</li>
    </ol>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <!-- Summary Cards - Grid Layout Compact -->
            <div class="row mb-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-gradient-primary py-2">
                            <h3 class="card-title mb-0" style="font-size: 1rem;">
                                <i class="fas fa-chart-pie mr-1"></i>Ringkasan Status Presensi
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-light px-2 py-1" id="summaryDate" style="font-size: 0.8rem;">
                                    <i class="far fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="row">
                                <!-- Total Presensi -->
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="summary-card-grid bg-gradient-info">
                                        <div class="summary-icon-grid"><i class="fas fa-users"></i></div>
                                        <div class="summary-number-grid" id="totalPresensi">0</div>
                                        <div class="summary-text-grid">Total</div>
                                    </div>
                                </div>
                                <!-- Tepat Waktu -->
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="summary-card-grid bg-gradient-success">
                                        <div class="summary-icon-grid"><i class="fas fa-check-circle"></i></div>
                                        <div class="summary-number-grid" id="tepatWaktu">0</div>
                                        <div class="summary-text-grid">Tepat Waktu</div>
                                    </div>
                                </div>
                                <!-- Terlambat -->
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="summary-card-grid bg-gradient-warning">
                                        <div class="summary-icon-grid"><i class="fas fa-clock"></i></div>
                                        <div class="summary-number-grid" id="terlambat">0</div>
                                        <div class="summary-text-grid">Terlambat</div>
                                    </div>
                                </div>
                                <!-- Tidak Hadir -->
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="summary-card-grid bg-gradient-danger">
                                        <div class="summary-icon-grid"><i class="fas fa-times-circle"></i></div>
                                        <div class="summary-number-grid" id="tidakHadir">0</div>
                                        <div class="summary-text-grid">Tidak Hadir</div>
                                    </div>
                                </div>
                                <!-- Cuti -->
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="summary-card-grid bg-gradient-primary">
                                        <div class="summary-icon-grid"><i class="fas fa-calendar-times"></i></div>
                                        <div class="summary-number-grid" id="cuti">0</div>
                                        <div class="summary-text-grid">Cuti</div>
                                    </div>
                                </div>
                                <!-- Sakit -->
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="summary-card-grid bg-gradient-secondary">
                                        <div class="summary-icon-grid"><i class="fas fa-briefcase-medical"></i></div>
                                        <div class="summary-number-grid" id="sakit">0</div>
                                        <div class="summary-text-grid">Sakit</div>
                                    </div>
                                </div>
                                <!-- Lembur -->
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="summary-card-grid bg-gradient-purple">
                                        <div class="summary-icon-grid"><i class="fas fa-business-time"></i></div>
                                        <div class="summary-number-grid" id="lembur">0</div>
                                        <div class="summary-text-grid">Lembur</div>
                                    </div>
                                </div>
                                <!-- Tugas Luar -->
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="summary-card-grid bg-gradient-indigo">
                                        <div class="summary-icon-grid"><i class="fas fa-car"></i></div>
                                        <div class="summary-number-grid" id="tugasLuar">0</div>
                                        <div class="summary-text-grid">Tugas Luar</div>
                                    </div>
                                </div>
                                <!-- Belum Lengkap -->
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="summary-card-grid bg-gradient-orange">
                                        <div class="summary-icon-grid"><i class="fas fa-exclamation-triangle"></i></div>
                                        <div class="summary-number-grid" id="belumLengkap">0</div>
                                        <div class="summary-text-grid">Belum Lengkap</div>
                                    </div>
                                </div>
                                <!-- WFA -->
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="summary-card-grid bg-gradient-teal">
                                        <div class="summary-icon-grid"><i class="fas fa-laptop-house"></i></div>
                                        <div class="summary-number-grid" id="wfa">0</div>
                                        <div class="summary-text-grid">WFA</div>
                                    </div>
                                </div>
                                <!-- WFO -->
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-2">
                                    <div class="summary-card-grid bg-gradient-lime">
                                        <div class="summary-icon-grid"><i class="fas fa-building"></i></div>
                                        <div class="summary-number-grid" id="wfo">0</div>
                                        <div class="summary-text-grid">WFO</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content - Split Layout -->
            <div class="row">
                <!-- Filter Section Global -->
                <div class="col-12 mb-2">
                    <div id="filterSection" style="display: none;">
                        <div class="alert alert-dismissible" style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 0;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="font-size: 1.5rem; opacity: 0.7;">&times;</button>
                            <div class="row align-items-center">
                                <div class="col-md-12 mb-2">
                                    <h6 class="mb-0" style="color: #0369a1; font-weight: 600;">
                                        <i class="fas fa-filter mr-2"></i> Filter Data Presensi
                                    </h6>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label for="filterDate" class="mb-1" style="color: #0c4a6e; font-weight: 600; font-size: 0.85rem;">
                                            <i class="far fa-calendar-alt mr-1"></i> Pilih Tanggal
                                        </label>
                                        <input type="date" class="form-control form-control-sm" id="filterDate" name="tanggal" value="{{ $selectedDate }}"
                                               style="border-radius: 6px; border: 2px solid #0891b2; padding: 8px 12px; font-weight: 500;">
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-sm btn-block" id="applyFilterBtn"
                                            style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                                   border: none;
                                                   color: white;
                                                   font-weight: 600;
                                                   padding: 8px 16px;
                                                   border-radius: 6px;
                                                   box-shadow: 0 2px 4px rgba(16, 185, 129, 0.4);">
                                        <i class="fas fa-check mr-1"></i> Terapkan
                                    </button>
                                </div>
                                <div class="col-md-7">
                                    <div class="p-2 bg-white rounded" style="border-left: 3px solid #0891b2;">
                                        <small class="d-block mb-1" style="color: #64748b;">
                                            <i class="fas fa-info-circle mr-1 text-info"></i>
                                            <strong>Tanggal:</strong>
                                            <strong id="currentDateLabel" style="color: #0369a1;">
                                                {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
                                            </strong>
                                        </small>
                                        <small class="text-muted">
                                            Filter akan update: Ringkasan, Tabel & Peta
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table (Kiri) -->
                <div class="col-lg-7 col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header py-2">
                            <h3 class="card-title mb-0" style="font-size: 1rem;">
                                <i class="fas fa-table mr-1"></i> Data Presensi
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm btn-primary" id="toggleFilterBtn">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-day mr-1 text-primary"></i>
                                    <span id="selectedDate">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d M Y') }}</span>
                                </small>
                            </div>
                            <div class="table-responsive">
                                <table id="presensiTable" class="table table-bordered table-striped table-hover table-sm w-100" style="font-size: 0.85rem;">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="20%">Nama</th>
                                        <th width="12%">Tipe</th>
                                        <th width="12%">Kantor</th>
                                        <th width="10%">Masuk</th>
                                        <th width="10%">Pulang</th>
                                        <th width="10%">Total</th>
                                        <th width="10%">Status</th>
                                        <th width="13%">Lokasi</th>
                                        <th width="13%">#</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Peta Sebaran (Kanan) -->
                <div class="col-lg-5 col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header py-2">
                            <h3 class="card-title mb-0" style="font-size: 1rem;">
                                <i class="fas fa-map-marked-alt mr-1"></i> Peta Sebaran
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" id="refreshMapBtn" title="Refresh Peta">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <!-- Loading indicator -->
                            <div id="mapLoading" class="text-center py-3" style="display: none;">
                                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                                <p class="mt-2 mb-0 small">Memuat peta...</p>
                            </div>

                            <!-- Map container -->
                            <div id="attendanceMap" style="height: 500px; border-radius: 5px;"></div>

                            <!-- Map info -->
                            <div class="mt-2">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="d-block">
                                            <span class="badge badge-success">●</span> Masuk
                                        </small>
                                    </div>
                                    <div class="col-6">
                                        <small class="d-block">
                                            <span class="badge badge-warning">●</span> Pulang
                                        </small>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    Total Marker: <strong id="markerCount">0</strong>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        $(function() {
            // --- INISIALISASI PETA ---
            var map = null;
            var markersLayer = null;

            function initMap() {
                try {
                    if (map !== null) {
                        map.remove();
                    }

                    map = L.map('attendanceMap', {
                        center: [-6.200000, 106.816666],
                        zoom: 12,
                        zoomControl: true,
                        scrollWheelZoom: true
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        maxZoom: 19,
                        minZoom: 5
                    }).addTo(map);

                    markersLayer = L.layerGroup().addTo(map);

                    setTimeout(function() {
                        map.invalidateSize();
                    }, 100);

                    console.log('Map initialized successfully');
                } catch (error) {
                    console.error('Error initializing map:', error);
                    toastr.error('Gagal menginisialisasi peta');
                }
            }

            // Fungsi Format Tanggal Indonesia
            function formatTanggalIndonesia(dateString) {
                if (!dateString) return 'Semua Tanggal';
                let dateObj = new Date(dateString + 'T00:00:00');
                let options = { year: 'numeric', month: 'long', day: 'numeric' };
                return dateObj.toLocaleDateString('id-ID', options);
            }

            // Fungsi Format Tanggal Pendek
            function formatTanggalPendek(dateString) {
                if (!dateString) return 'Semua';
                let dateObj = new Date(dateString + 'T00:00:00');
                let options = { year: 'numeric', month: 'short', day: 'numeric' };
                return dateObj.toLocaleDateString('id-ID', options);
            }

            // Fungsi Update Semua Label Tanggal
            function updateAllDateLabels(dateString) {
                $('#selectedDate').text(formatTanggalPendek(dateString));
                $('#mapDateLabel').text(formatTanggalIndonesia(dateString));
                $('#currentDateLabel').text(formatTanggalIndonesia(dateString));
            }

            // Fungsi Load Data Peta dengan Icon Berwarna
            function loadMapData(tanggal) {
                if (!map || !markersLayer) {
                    console.error('Map not initialized');
                    return;
                }

                $('#mapLoading').show();
                $('#attendanceMap').css('opacity', '0.5');
                markersLayer.clearLayers();

                $.ajax({
                    url: "{{ route('presensi.data') }}",
                    data: {
                        tanggal: tanggal,
                        length: -1
                    },
                    success: function(response) {
                        let data = response.data;
                        let validMarkers = 0;

                        if(data && data.length > 0) {
                            let bounds = [];

                            data.forEach(function(item) {
                                // Marker MASUK (Hijau)
                                if (item.koordinat_masuk) {
                                    let coords = item.koordinat_masuk.split(',');
                                    let lat = parseFloat(coords[0].trim());
                                    let lng = parseFloat(coords[1].trim());

                                    if (!isNaN(lat) && !isNaN(lng)) {
                                        let greenIcon = L.icon({
                                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                                            iconSize: [25, 41],
                                            iconAnchor: [12, 41],
                                            popupAnchor: [1, -34],
                                            shadowSize: [41, 41]
                                        });

                                        let statusBadge = item.status === 'Tepat Waktu' ?
                                            '<span class="badge badge-success">Tepat Waktu</span>' :
                                            '<span class="badge badge-warning">Terlambat</span>';

                                        let popupContent = `
                                            <div style="min-width: 200px;">
                                                <h6 class="mb-2"><strong>${item.nama_karyawan}</strong></h6>
                                                <hr class="my-2">
                                                <small class="d-block mb-1">
                                                    <i class="fas fa-sign-in-alt text-success mr-1"></i>
                                                    <strong>Jam Masuk:</strong> ${item.jam_masuk || '-'}
                                                </small>
                                                <small class="d-block mb-1">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    <strong>Jadwal:</strong> ${item.jam_harus_masuk_akhir || '-'}
                                                </small>
                                                <small class="d-block mb-2">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    <strong>Status:</strong> ${statusBadge}
                                                </small>
                                                <small class="text-muted">
                                                    <i class="fas fa-map-pin mr-1"></i>${lat.toFixed(6)}, ${lng.toFixed(6)}
                                                </small>
                                            </div>
                                        `;

                                        let marker = L.marker([lat, lng], {
                                            icon: greenIcon,
                                            title: item.nama_karyawan + ' - Masuk'
                                        }).bindPopup(popupContent, {
                                            maxWidth: 300,
                                            className: 'custom-popup'
                                        });

                                        markersLayer.addLayer(marker);
                                        bounds.push([lat, lng]);
                                        validMarkers++;
                                    }
                                }

                                // Marker PULANG (Kuning)
                                if (item.koordinat_pulang) {
                                    let coords = item.koordinat_pulang.split(',');
                                    let lat = parseFloat(coords[0].trim());
                                    let lng = parseFloat(coords[1].trim());

                                    if (!isNaN(lat) && !isNaN(lng)) {
                                        let yellowIcon = L.icon({
                                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png',
                                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                                            iconSize: [25, 41],
                                            iconAnchor: [12, 41],
                                            popupAnchor: [1, -34],
                                            shadowSize: [41, 41]
                                        });

                                        let popupContent = `
                                            <div style="min-width: 200px;">
                                                <h6 class="mb-2"><strong>${item.nama_karyawan}</strong></h6>
                                                <hr class="my-2">
                                                <small class="d-block mb-1">
                                                    <i class="fas fa-sign-out-alt text-warning mr-1"></i>
                                                    <strong>Jam Pulang:</strong> ${item.jam_pulang || '-'}
                                                </small>
                                                <small class="d-block mb-1">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    <strong>Jadwal:</strong> ${item.jam_harus_pulang_awal || '-'}
                                                </small>
                                                <small class="text-muted">
                                                    <i class="fas fa-map-pin mr-1"></i>${lat.toFixed(6)}, ${lng.toFixed(6)}
                                                </small>
                                            </div>
                                        `;

                                        let marker = L.marker([lat, lng], {
                                            icon: yellowIcon,
                                            title: item.nama_karyawan + ' - Pulang'
                                        }).bindPopup(popupContent, {
                                            maxWidth: 300,
                                            className: 'custom-popup'
                                        });

                                        markersLayer.addLayer(marker);
                                        bounds.push([lat, lng]);
                                        validMarkers++;
                                    }
                                }
                            });

                            if (bounds.length > 0) {
                                map.fitBounds(bounds, {
                                    padding: [50, 50],
                                    maxZoom: 15
                                });
                            } else {
                                map.setView([-6.200000, 106.816666], 12);
                            }

                            $('#markerCount').text(validMarkers);
                        } else {
                            map.setView([-6.200000, 106.816666], 12);
                            $('#markerCount').text(0);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading map data:', xhr);
                        toastr.error('Gagal memuat data peta');
                    },
                    complete: function() {
                        $('#mapLoading').hide();
                        $('#attendanceMap').css('opacity', '1');

                        setTimeout(function() {
                            if (map) map.invalidateSize();
                        }, 200);
                    }
                });
            }

            // Refresh Map Button
            $('#refreshMapBtn').on('click', function() {
                $(this).find('i').addClass('fa-spin');
                loadMapData($('#filterDate').val());
                setTimeout(() => {
                    $(this).find('i').removeClass('fa-spin');
                }, 1000);
            });

            // --- TOGGLE FILTER ---
            $('#toggleFilterBtn').on('click', function() {
                $('#filterSection').slideToggle(300);
            });

            // --- DATATABLE ---
            let presensiTable = $('#presensiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('presensi.data') }}",
                    data: function (d) {
                        d.tanggal = $('#filterDate').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama_karyawan', name: 'karyawan.fullName' },
                    { data: 'type_presensi', name: 'type_presensi' },
                    { data: 'kantor', name: 'kantor', orderable: false },
                    { data: 'jam_masuk', name: 'jam_masuk' },
                    { data: 'jam_pulang', name: 'jam_pulang' },
                    {
                        data: 'total_jam_kerja',
                        name: 'total_jam_kerja',
                        render: function(data, type, row) {
                            if (data && data > 0) {
                                return '<span class="badge badge-info">' + parseFloat(data).toFixed(1) + ' jam</span>';
                            }
                            return '<span class="text-muted">-</span>';
                        }
                    },
                    { data: 'status', name: 'status' },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        render: function(data, type, row) {
                            if (data && data.length > 30) {
                                return '<span title="' + data + '">' + data.substring(0, 30) + '...</span>';
                            }
                            return data || '-';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let buttons = '';

                            // Button Lokasi Masuk
                            if (row.lat_masuk && row.lng_masuk) {
                                buttons += '<button class="btn btn-sm btn-outline-success view-location mr-1" ' +
                                    'data-lat="' + row.lat_masuk + '" ' +
                                    'data-lng="' + row.lng_masuk + '" ' +
                                    'data-type="masuk" ' +
                                    'data-name="' + (row.karyawan ? row.karyawan.fullName : 'N/A') + '" ' +
                                    'title="Lihat Lokasi Masuk">' +
                                    '<i class="fas fa-map-marker-alt"></i>' +
                                    '</button>';
                            }

                            // Button Lokasi Pulang
                            if (row.lat_pulang && row.lng_pulang) {
                                buttons += '<button class="btn btn-sm btn-outline-warning view-location" ' +
                                    'data-lat="' + row.lat_pulang + '" ' +
                                    'data-lng="' + row.lng_pulang + '" ' +
                                    'data-type="pulang" ' +
                                    'data-name="' + (row.karyawan ? row.karyawan.fullName : 'N/A') + '" ' +
                                    'title="Lihat Lokasi Pulang">' +
                                    '<i class="fas fa-map-marker-alt"></i>' +
                                    '</button>';
                            }

                            return buttons || '<span class="text-muted">-</span>';
                        }
                    }
                ],
                order: [[1, 'asc']],
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],

                // Layout DOM
                dom: "<'row'<'col-md-6 d-flex align-items-center'lB><'col-md-6'f>>" +
                    "<'row'<'col-md-12'tr>>" +
                    "<'row'<'col-md-5'i><'col-md-7'p>>",

                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm ml-3',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                            modifier: {
                                page: 'all'
                            }
                        },
                        title: 'Data Presensi - ' + $('#filterDate').val()
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm ml-1',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7],
                            modifier: {
                                page: 'all'
                            }
                        },
                        title: 'Data Presensi - ' + $('#filterDate').val(),
                        orientation: 'landscape',
                        pageSize: 'A4'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-info btn-sm ml-1',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
                            modifier: {
                                page: 'all'
                            }
                        },
                        title: 'Data Presensi - ' + $('#filterDate').val()
                    }
                ],
                drawCallback: function() {
                    updateSummary();
                },
                language: {
                    processing: '<i class="fas fa-spinner fa-spin fa-3x text-primary"></i><br>Memuat data...',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    zeroRecords: 'Data tidak ditemukan',
                    info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    search: 'Cari:',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Selanjutnya',
                        previous: 'Sebelumnya'
                    }
                }
            });

            // --- EVENT HANDLER BUTTON VIEW LOCATION ---
            $(document).on('click', '.view-location', function() {
                let lat = parseFloat($(this).data('lat'));
                let lng = parseFloat($(this).data('lng'));
                let type = $(this).data('type');
                let name = $(this).data('name');

                if (isNaN(lat) || isNaN(lng)) {
                    toastr.error('Koordinat tidak valid');
                    return;
                }

                // Scroll ke map
                $('html, body').animate({
                    scrollTop: $("#attendanceMap").offset().top - 100
                }, 500);

                // Zoom ke lokasi
                map.setView([lat, lng], 17, {
                    animate: true,
                    duration: 1
                });

                // Buka popup marker yang sesuai
                setTimeout(function() {
                    markersLayer.eachLayer(function(layer) {
                        if (layer instanceof L.Marker) {
                            let markerLatLng = layer.getLatLng();
                            if (Math.abs(markerLatLng.lat - lat) < 0.00001 &&
                                Math.abs(markerLatLng.lng - lng) < 0.00001) {
                                layer.openPopup();
                            }
                        }
                    });
                }, 600);

                // Notifikasi
                let typeText = type === 'masuk' ? 'Masuk' : 'Pulang';
                toastr.info(`Menampilkan lokasi ${typeText} - ${name}`);
            });

            // --- UPDATE SUMMARY ---
            function updateSummary() {
                let tanggal = $('#filterDate').val();

                $.ajax({
                    url: "{{ route('presensi.summary') }}",
                    method: 'GET',
                    data: { tanggal: tanggal },
                    success: function(response) {
                        // Total dan Status Utama
                        $('#totalPresensi').text(response.total || 0);
                        $('#tepatWaktu').text(response.good || 0);
                        $('#terlambat').text(response.late || 0);
                        $('#tidakHadir').text(response.absent || 0);
                        $('#cuti').text(response.leave || 0);
                        $('#sakit').text(response.sick || 0);

                        // Status Tambahan
                        $('#lembur').text(response.overtime || 0);
                        $('#tugasLuar').text(response.onduty || 0);
                        $('#belumLengkap').text(response.uncompleted || 0);

                        // Tipe Presensi
                        $('#wfa').text(response.wfa || 0);
                        $('#wfo').text(response.wfo || 0);
                    },
                    error: function(xhr) {
                        console.error('Error loading summary:', xhr);
                        // Set default values on error
                        $('#totalPresensi, #tepatWaktu, #terlambat, #tidakHadir, #cuti, #sakit, #lembur, #tugasLuar, #belumLengkap, #wfa, #wfo').text('0');
                    }
                });
            }

            // --- APPLY FILTER BUTTON ---
            $('#applyFilterBtn').on('click', function() {
                let selectedDate = $('#filterDate').val();

                // Update semua label tanggal
                updateAllDateLabels(selectedDate);

                // Reload semua data
                presensiTable.ajax.reload();
                updateSummary();
                loadMapData(selectedDate);

                // Tutup filter section
                $('#filterSection').slideUp(300);

                // Notifikasi
                toastr.success('Filter berhasil diterapkan untuk tanggal ' + formatTanggalIndonesia(selectedDate));
            });

            // --- TOMBOL SINKRONISASI ---
            $('#syncBtn').on('click', function() {
                let btn = $(this);
                let originalHtml = btn.html();

                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sinkronisasi...');

                $.ajax({
                    url: "{{ route('presensi.sync') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        tanggal: $('#filterDate').val()
                    },
                    success: function(response) {
                        toastr.success(response.message || 'Sinkronisasi berhasil!');
                        presensiTable.ajax.reload();
                        updateSummary();
                        loadMapData($('#filterDate').val());
                    },
                    error: function(xhr) {
                        let message = xhr.responseJSON?.message || 'Gagal melakukan sinkronisasi';
                        toastr.error(message);
                    },
                    complete: function() {
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // Initialize map
            initMap();

            // --- TAB EVENTS ---
            // Saat tab map diklik, resize map dan load data
            $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
                let target = $(e.target).attr("href");

                if (target === '#mapView') {
                    // Delay untuk memastikan tab sudah fully shown
                    setTimeout(function() {
                        if (map) {
                            map.invalidateSize();
                            loadMapData($('#filterDate').val());
                        }
                    }, 100);
                }
            });

            // Load data awal
            setTimeout(function() {
                let initialDate = $('#filterDate').val();
                updateAllDateLabels(initialDate);
                updateSummary();
            }, 300);
        });
    </script>
@endsection
