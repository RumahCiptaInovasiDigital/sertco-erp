<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detail Lokasi Presensi</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama_lokasi">Nama Lokasi</label>
                        <input type="text" class="form-control" id="nama_lokasi" placeholder="Masukkan Nama Lokasi">
                    </div>
                    <div class="form-group">
                        <label for="kode_lokasi">Kode Lokasi</label>
                        <input type="text" class="form-control" id="kode_lokasi" placeholder="Masukkan Kode Lokasi">
                    </div>
                    <div class="form-group">
                        <label for="jam_masuk">Jam Masuk</label>
                        <input type="time" class="form-control" id="jam_masuk">
                    </div>
                    <div class="form-group">
                        <label for="jam_pulang">Jam Pulang</label>
                        <input type="time" class="form-control" id="jam_pulang">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Lokasi Peta</h3>
                </div>
                <div class="card-body">
                    {{-- Placeholder for Leaflet Map --}}
                    <div id="mapid" style="height: 400px; width: 100%;"></div>
                    <small class="form-text text-muted">Integrasikan Leaflet JS/CSS di sini untuk menampilkan peta.</small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    {{-- Add Leaflet CSS here --}}
    {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" /> --}}
@endpush

@push('scripts')
    {{-- Add Leaflet JS here --}}
    {{-- <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script> --}}
    <script>
        // Placeholder for Leaflet map initialization
        // var map = L.map('mapid').setView([-6.2088, 106.8456], 13); // Example coordinates (Jakarta)
        // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        // }).addTo(map);
        // L.marker([-6.2088, 106.8456]).addTo(map) // Example marker
        //     .bindPopup('Lokasi Anda')
        //     .openPopup();
    </script>
@endpush