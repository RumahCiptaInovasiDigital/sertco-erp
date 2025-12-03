<div class="row">
    <div class="col-12">
        <h4>Recent Activity</h4>
        @foreach ($projectLog as $log)
        @php
        // Cek apakah notifikasi dari hari ini
        $isToday = Carbon\Carbon::parse($log->created_at)->isToday();
        @endphp
        <div class="post">
            <div class="user-block">
                <img class="img-circle img-bordered-sm" src="{{ asset('dist/img/sq-logo.jpg') }}" alt="user image">
                <span class="username">
                    <a href="#">{{ $log->karyawan->fullName }}</a>
                </span>
                <span class="description">{{ $log->created_at->format('g:i A') }} {{ $isToday ? 'Hari ini' : $log->created_at->format('d M Y') }}</span>
            </div>

            <span class="badge badge-info">Status: </span>
            <p>
                <i class="fas fa-tag"></i> {{ $log->keterangan }}
            </p>
            {{-- <div class="callout callout-warning">
            </div> --}}
        </div>
        @endforeach
    </div>
</div>