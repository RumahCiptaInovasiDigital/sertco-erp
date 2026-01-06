<li class="nav-header">ADMIN TOOLS</li>
@foreach ($relation ?? [] as $item)
    @if (Str::is('admin.permission.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('admin.permission.index') }}" class="nav-link {{ request()->is('admin/permission*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-key"></i>
                <p>
                    Permissions
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach
@foreach ($relation ?? [] as $item)
    @if (Str::is('admin.notification.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('admin.notification.index') }}" class="nav-link {{ request()->is('admin/notification*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-bell"></i>
                <p>
                    Notification
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach
@foreach ($relation ?? [] as $item)
    @if (Str::is('admin.setting.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('admin.setting.index') }}" class="nav-link {{ request()->is('admin/setting*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                    Page Setting
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach
{{-- Feedback Section --}}
@if (auth()->user()->nik === 'SQ-ADM-999')
    <li class="nav-item">
        <a href="{{ route('admin.feedback.index') }}" class="nav-link {{ request()->is('admin/feedback*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-comment"></i>
        <p>
            User Feedback
        </p>
    </a>
</li>
@endif