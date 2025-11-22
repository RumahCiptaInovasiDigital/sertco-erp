<li class="nav-header">Project</li>
@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.pes.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('v1.pes.index') }}" class="nav-link {{ request()->is('v1/register-project*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-invoice"></i>
                <p>
                    Register Project
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach
@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.pes.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('v1.pes.index') }}" class="nav-link {{ request()->is('v1/pes*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-contract"></i>
                <p>
                    Project Execution Sheet
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach

{{-- <li class="nav-header">Approval</li>
@foreach ($relation ?? [] as $item)
    @if (Str::is('v1.pes.index', $item->url))
        <li class="nav-item">
            <a href="{{ route('v1.approval.pes.index') }}" class="nav-link {{ request()->is('v1/approval/pes*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-check-circle"></i>
                <p>
                    PES Approval (T&O)
                </p>
            </a>
        </li>
        @break
    @endif
@endforeach --}}