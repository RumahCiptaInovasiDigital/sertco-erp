<a class="nav-link" data-toggle="dropdown" href="#">
    <i class="far fa-bell"></i>
    <span class="badge badge-warning navbar-badge">{{ auth()->user()->notify()->count() }}</span>
</a>
<div class="dropdown-menu dropdown-menu-xl dropdown-menu-right w-xs-100">
    <span class="dropdown-header"><i class="fas fa-envelope mr-2"></i> {{ auth()->user()->notify()->count() }} Notifications</span>
    <div style="max-height: 400px; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
        <style>
            .dropdown-menu div::-webkit-scrollbar {
                display: none;
            }
        </style>
        @forelse (auth()->user()->notify() as $notif)
            @php
            // Cek apakah notifikasi dari hari ini
            $isToday = Carbon\Carbon::parse($notif->created_at)->isToday();
            // Format waktu relative seperti "2 min ago"
            $relativeTime = Carbon\Carbon::parse($notif->created_at)->diffForHumans();
            @endphp
                <div class="dropdown-divider"></div>
                <div class="dropdown-item">
                    <div class="card mb-0">
                        <div class="card-body pb-1">
                            <div class="row">
                                <div class="col-12">
                                    <h6 class=" text-bold text-wrap mb-2">
                                        <i class="fas fa-bell mr-2"></i>{{ $notif->notifikasi->title }}
                                    </h6>
                                    <span class="text-wrap" style="font-size: 14px;">
                                        {{ $notif->notifikasi->pesan }}
                                    </span>
                                </div>
                                <div class="col-12 mb-1 mt-2">
                                    <span class="text-muted text-sm">{{ $relativeTime }}</span>
                                    <span class="badge {{ $isToday ? 'badge-success' : 'badge-warning' }} my-1 float-right">{{ $isToday ? 'Hari ini' : 'Kemarin' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="dropdown-divider"></div>
                <span class="dropdown-item" style="text-align: center;">
                    Tidak Ada Notifikasi
                </span>
            @endforelse
        </div>
    <div class="dropdown-divider"></div>
    <a href="#" class="dropdown-item text-reset" style="text-align: center;">
        <small><i class="fas fa-check mr-2"></i>mark all as read</small>
    </a>
</div>