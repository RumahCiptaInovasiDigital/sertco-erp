@extends('layouts.master')
@section('title', 'Data Kantor Cabang')
@section('PageTitle', 'Data Kantor Cabang')

@section('head')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map, #mapView, #mapAll {
            height: 400px;
            width: 100%;
            border-radius: 5px;
        }
        .info-box {
            padding: 10px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        /* make right column map scrollable if content taller than viewport */
        .map-card .card-body { padding: .75rem; }
        @media (min-width: 768px) {
            /* ensure both columns align height */
            .row-equal > [class*='col-'] { display: flex; flex-direction: column; }
            .row-equal .card { flex: 1; display: flex; flex-direction: column; }
            .row-equal .card .card-body { flex: 1; }
        }
    </style>
@endsection

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Master</a></li>
        <li class="breadcrumb-item active">Data Kantor Cabang</li>
    </ol>
@endsection

@section('content')
    <div class="row row-equal">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="card-tools">
                        <button id="btn-tambah" type="button" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Kantor
                        </button>
                        <button id="reload-datatable-btn" class="btn btn-secondary" title="Muat Ulang Tabel">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button id="refresh-mapall-btn" class="btn btn-info" title="Refresh Semua Titik">
                            <i class="fas fa-map-marked-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tabelKantor" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th style="width: 10px">No.</th>
                            <th>Nama Kantor</th>
                            <th>Alamat</th>
                            <th>Kota</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right column: map all branches (on small screens this becomes below) -->
        <div class="col-12 col-md-4 order-last order-md-0">
            <div class="card map-card">
                <div class="card-header">
                    <h3 class="card-title">Peta Semua Cabang</h3>
                    <div class="card-tools">
                        <button id="zoom-fit-btn" class="btn btn-sm btn-outline-primary" title="Zoom ke Semua Titik">
                            <i class="fas fa-compress-arrows-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="mapAll"></div>
                    <small class="form-text text-muted mt-2">Tampilan semua titik kantor. Gunakan tombol refresh jika data baru ditambahkan.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form Kantor -->
    <div class="modal fade" id="modalKantor" tabindex="-1" role="dialog" aria-labelledby="modalKantorLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKantorLabel">Form Kantor Cabang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formKantor">
                    <div class="modal-body">
                        <input type="hidden" id="kantor_id" name="id">

                        <div class="form-group">
                            <label for="name">Nama Kantor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama Kantor" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Telepon</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Contoh: 08123456789">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Contoh: kantor@email.com">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="address">Alamat</label>
                                    <textarea class="form-control" id="address" name="address" rows="2" placeholder="Masukkan Alamat Lengkap"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city">Kota</label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="Contoh: Jakarta">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <p class="font-weight-bold">Pengaturan Lokasi Presensi</p>

                        <div class="form-group">
                            <label for="radius">Radius (meter) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="radius" name="radius" placeholder="Contoh: 100" min="1" required>
                            <small class="form-text text-muted">Radius untuk area presensi di sekitar titik lokasi</small>
                        </div>

                        <div class="form-group">
                            <label>Lokasi Koordinat <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Latitude (contoh: -6.2088)" required>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Longitude (contoh: 106.8456)" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-info btn-block" id="btnGetLocation" title="Ambil Lokasi Saat Ini">
                                        <i class="fas fa-crosshairs"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">Klik pada peta, ketik manual, atau tekan tombol GPS untuk lokasi saat ini</small>
                        </div>

                        <div class="form-group">
                            <label>Peta Lokasi</label>
                            <div id="map"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" id="submitBtn" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal View Peta -->
    <div class="modal fade" id="modalViewMap" tabindex="-1" role="dialog" aria-labelledby="modalViewMapLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalViewMapLabel">Lokasi Kantor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="info-box mb-3">
                        <h6 id="viewKantorNama"></h6>
                        <p id="viewKantorAlamat" class="mb-1"></p>
                        <small id="viewKantorKoordinat" class="text-muted"></small>
                    </div>
                    <div id="mapView"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        $(document).ready(function () {

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            var map, marker, circle;
            var mapView;
            var mapAll, mapAllGroup;
            var mapAllMarkers = {};
            var mapAllCircles = {};
            var defaultLat = -6.2088;
            var defaultLng = 106.8456;
            var currentLat = defaultLat;
            var currentLng = defaultLng;

            var tabelKantor = $("#tabelKantor").DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('master.kantor.get') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'address', name: 'address' },
                    { data: 'city', name: 'city' },
                    { data: 'phone', name: 'phone' },
                    { data: 'email', name: 'email' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                responsive: true,
                autoWidth: false
            });

            $('#reload-datatable-btn').on('click', function() {
                tabelKantor.ajax.reload();
                loadAllMarkers();
            });

            function initMapAll() {
                if (mapAll) { mapAll.remove(); mapAll = null; }
                mapAll = L.map('mapAll').setView([defaultLat, defaultLng], 11);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(mapAll);
                mapAllGroup = L.featureGroup().addTo(mapAll);
                mapAllMarkers = {};
                mapAllCircles = {};
                setTimeout(() => mapAll.invalidateSize(), 200);
            }

            // helper: robustly extract array from various response shapes
            function unwrapArray(resp) {
                if (!resp) return [];
                if (Array.isArray(resp)) return resp;
                if (resp.data) {
                    if (Array.isArray(resp.data)) return resp.data;
                    if (resp.data.data && Array.isArray(resp.data.data)) return resp.data.data;
                }
                if (resp.rows && Array.isArray(resp.rows)) return resp.rows;
                if (resp.result && Array.isArray(resp.result)) return resp.result;
                // last resort: try to find array value in object
                for (var k in resp) {
                    if (Array.isArray(resp[k])) return resp[k];
                }
                return [];
            }

            // return the jqXHR promise
            function loadAllMarkers() {
                if (!mapAll) initMapAll();
                mapAllGroup.clearLayers();
                mapAllMarkers = {};
                mapAllCircles = {};

                return $.get('{{ url("master/kantor-all") }}')
                    .done(function(response) {
                        console.log('master/kantor-all response:', response);
                        var items = unwrapArray(response);
                        var bounds = [];
                        items.forEach(function(item) {
                            var lat = parseFloat(item.latitude);
                            var lng = parseFloat(item.longitude);
                            if (isNaN(lat) || isNaN(lng)) return;
                            var popupHtml = '<b>' + (item.name||'') + '</b><br>' + (item.address||'') + '<br><small>' + (item.city||'') + '</small>';
                            var m = L.marker([lat, lng]).bindPopup(popupHtml);
                            var c = L.circle([lat, lng], {
                                color: 'green',
                                fillColor: '#3f3',
                                fillOpacity: 0.15,
                                radius: parseFloat(item.radius || 100)
                            });
                            mapAllGroup.addLayer(m);
                            mapAllGroup.addLayer(c);
                            if (item.id !== undefined && item.id !== null) {
                                mapAllMarkers[item.id] = m;
                                mapAllCircles[item.id] = c;
                            }
                            bounds.push([lat, lng]);
                        });

                        // make sure map size is correct before fitting
                        setTimeout(function() {
                            try {
                                mapAll.invalidateSize();
                            } catch(e) {}
                            if (bounds.length) {
                                try {
                                    mapAll.fitBounds(bounds, { padding: [40,40] });
                                } catch(e) {
                                    // fallback to first marker
                                    var b = bounds[0];
                                    mapAll.setView(b, 12);
                                }
                            } else {
                                mapAll.setView([defaultLat, defaultLng], 11);
                            }
                        }, 120);
                    })
                    .fail(function(xhr, status, err) {
                        console.error('Failed to load kantor-all:', status, err);
                        Swal.fire({ icon: 'error', title: 'Gagal Memuat Titik', text: 'Tidak dapat memuat data koordinat. Periksa response endpoint.' });
                    });
            }

            $('#refresh-mapall-btn').on('click', function() {
                loadAllMarkers();
            });

            $('#zoom-fit-btn').on('click', function() {
                if (mapAllGroup && mapAllGroup.getLayers().length) {
                    mapAll.fitBounds(mapAllGroup.getBounds(), { padding: [40,40] });
                }
            });

            // click lokasi in table -> focus marker on mapAll
            $('#tabelKantor tbody').on('click', '.loc-btn', function () {
                var id = $(this).data('id');
                if (!mapAll) initMapAll();
                loadAllMarkers().done(function() {
                    var marker = mapAllMarkers[id];
                    var circle = mapAllCircles[id];
                    if (marker) {
                        var layers = [marker];
                        if (circle) layers.push(circle);
                        var fg = L.featureGroup(layers);
                        try {
                            mapAll.fitBounds(fg.getBounds(), { padding: [40,40] });
                        } catch(e) {
                            mapAll.setView(marker.getLatLng(), 15);
                        }
                        marker.openPopup();
                        if (circle) {
                            var original = { color: circle.options.color, fillColor: circle.options.fillColor };
                            circle.setStyle({ color: 'orange', fillColor: '#ffa500' });
                            setTimeout(function() { circle.setStyle(original); }, 2000);
                        }
                    } else {
                        Swal.fire({ icon: 'warning', title: 'Titik tidak ditemukan', text: 'Koordinat tidak tersedia atau belum dimuat.' });
                    }
                    $('html, body').animate({ scrollTop: $("#mapAll").offset().top - 80 }, 400);
                });
            });

            // Initialize map for modal form
            function initMap() {
                if (map) { map.remove(); map = null; }
                map = L.map('map').setView([currentLat, currentLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                marker = L.marker([currentLat, currentLng], { draggable: true }).addTo(map);
                circle = L.circle([currentLat, currentLng], {
                    color: 'blue',
                    fillColor: '#30f',
                    fillOpacity: 0.2,
                    radius: 100
                }).addTo(map);

                marker.on('dragend', function(e) {
                    var pos = e.target.getLatLng();
                    updateMarker(pos.lat, pos.lng);
                });

                map.on('click', function(e) {
                    updateMarker(e.latlng.lat, e.latlng.lng);
                });

                setTimeout(() => map.invalidateSize(), 200);
            }

            function updateMarker(lat, lng) {
                currentLat = lat;
                currentLng = lng;
                $('#latitude').val(lat.toFixed(6));
                $('#longitude').val(lng.toFixed(6));
                marker.setLatLng([lat, lng]);
                circle.setLatLng([lat, lng]);
                map.panTo([lat, lng]);
            }

            function updateRadius(radius) {
                if (circle) {
                    circle.setRadius(parseFloat(radius));
                }
            }

            $('#radius').on('input', function() {
                var r = $(this).val();
                if (r && r > 0) updateRadius(r);
            });

            $('#latitude, #longitude').on('change', function() {
                var lat = parseFloat($('#latitude').val());
                var lng = parseFloat($('#longitude').val());
                if (!isNaN(lat) && !isNaN(lng)) {
                    updateMarker(lat, lng);
                }
            });

            $('#btnGetLocation').on('click', function() {
                if (navigator.geolocation) {
                    $(this).html('<i class="fas fa-spinner fa-spin"></i>');
                    var btn = $(this);
                    navigator.geolocation.getCurrentPosition(function(position) {
                        updateMarker(position.coords.latitude, position.coords.longitude);
                        btn.html('<i class="fas fa-crosshairs"></i>');
                    }, function(error) {
                        btn.html('<i class="fas fa-crosshairs"></i>');
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil lokasi: ' + error.message });
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Tidak Didukung', text: 'Browser tidak mendukung geolokasi' });
                }
            });

            // Tambah Kantor
            $('#btn-tambah').on('click', function() {
                $('#formKantor')[0].reset();
                $('#kantor_id').val('');
                $('#modalKantorLabel').text('Tambah Kantor Cabang');
                currentLat = defaultLat;
                currentLng = defaultLng;
                $('#latitude').val('');
                $('#longitude').val('');
                $('#radius').val('100');
                $('#modalKantor').modal('show');
                setTimeout(function() {
                    initMap();
                }, 300);
            });

            // Edit Kantor
            $('#tabelKantor tbody').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                $.get('{{ url("master/kantor-edit") }}/' + id, function(data) {
                    $('#kantor_id').val(data.id);
                    $('#name').val(data.name);
                    $('#address').val(data.address);
                    $('#city').val(data.city);
                    $('#phone').val(data.phone);
                    $('#email').val(data.email);
                    $('#latitude').val(data.latitude);
                    $('#longitude').val(data.longitude);
                    $('#radius').val(data.radius || 100);

                    currentLat = parseFloat(data.latitude) || defaultLat;
                    currentLng = parseFloat(data.longitude) || defaultLng;

                    $('#modalKantorLabel').text('Edit Kantor Cabang');
                    $('#modalKantor').modal('show');
                    setTimeout(function() {
                        initMap();
                    }, 300);
                }).fail(function() {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat data kantor' });
                });
            });

            // Submit Form
            $('#formKantor').on('submit', function(e) {
                e.preventDefault();
                var id = $('#kantor_id').val();
                var url = id ? '{{ url("master/kantor-update") }}/' + id : '{{ route("master.kantor.store") }}';
                var method = id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#modalKantor').modal('hide');
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.success });
                        tabelKantor.ajax.reload();
                        loadAllMarkers();
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON?.errors;
                        if (errors) {
                            var msg = Object.values(errors).flat().join('<br>');
                            Swal.fire({ icon: 'error', title: 'Validasi Gagal', html: msg });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat menyimpan data' });
                        }
                    }
                });
            });

            // Delete Kantor
            $('#tabelKantor tbody').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data kantor akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ url("master/kantor-delete") }}/' + id,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.success });
                                tabelKantor.ajax.reload();
                                loadAllMarkers();
                            },
                            error: function() {
                                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat menghapus data' });
                            }
                        });
                    }
                });
            });

            // initialize
            initMapAll();
            loadAllMarkers();
        });
    </script>


@endsection
