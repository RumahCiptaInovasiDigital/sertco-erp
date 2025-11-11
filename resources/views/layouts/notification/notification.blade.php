<a class="nav-link" data-toggle="dropdown" href="#">
    <i class="far fa-bell"></i>
    <span class="badge badge-warning navbar-badge">{{ auth()->user()->notify()->count() }}</span>
</a>
<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
    <span class="dropdown-header">{{ auth()->user()->notify()->count() }} Notifications</span>
    @forelse (auth()->user()->notify() as $notif)
        @php
        // Cek apakah notifikasi dari hari ini
        $isToday = Carbon\Carbon::parse($notif->created_at)->isToday();
        // Format waktu relative seperti "2 min ago"
        $relativeTime = Carbon\Carbon::parse($notif->created_at)->diffForHumans();
        @endphp
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> {{ $notif->notifikasi->title }}
            <span class="float-right text-muted text-sm">{{ $relativeTime }}</span>
        </a>
        
        <div class="dropdown-divider"></div>
    @empty
        <div class="dropdown-divider"></div>
        <span class="dropdown-item" style="text-align: center;">
            Tidak Ada Notifikasi
        </span>
    @endforelse
</div>