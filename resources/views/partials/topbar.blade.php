<header class="topbar">

    {{-- Mobile menu --}}
    <button
        class="topbar-menu-button"
        type="button"
        aria-label="Buka navigasi"
        data-sidebar-toggle
    >
        ☰
    </button>

    {{-- Page title --}}
    <div class="topbar-title">
        {{ $title
            ?? (
                request()->routeIs('reports.*')
                    ? __('messages.financial_reports')
                    : (
                        request()->routeIs('transactions.*')
                            ? __('messages.financial_data')
                            : __('messages.dashboard')
                    )
            )
        }}
    </div>

    {{-- Global Search --}}
    <form
        class="global-search"
        action="{{ route('transactions.index') }}"
        method="GET"
    >
        <span>⌕</span>

        <input
            name="search"
            value="{{ request('search') }}"
            placeholder="{{ __('messages.search_placeholder') }}"
        >
    </form>
    {{-- Topbar Actions --}}
    <div class="topbar-actions">

        {{-- Notifications --}}
        <div class="topbar-dropdown">
            <details class="dropdown-details">

                <summary
                    class="icon-button notification-button"
                    title="Notifikasi"
                    aria-label="Notifikasi"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        height="20"
                        viewBox="0 -960 960 960"
                        width="20"
                        fill="currentColor"
                    >
                        <path d="M160-200v-80h80v-280q0-83 50-147.5T420-792v-28q0-25 17.5-42.5T480-880q25 0 42.5 17.5T540-820v28q80 20 130 84.5T720-560v280h80v80H160Zm320-300Zm0 420q-33 0-56.5-23.5T400-160h160q0 33-23.5 56.5T480-80ZM320-280h320v-280q0-66-47-113t-113-47q-66 0-113 47t-47 113v280Z"/>
                    </svg>
                    @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                        <i></i>
                    @endif
                </summary>

                <div class="dropdown-menu">

                    <div class="dropdown-header">
                        {{ __('messages.notifications') }}
                    </div>

                    @forelse(auth()->user()->notifications->take(5) as $notification)
                        @php
                            $isSuccess =
                                ($notification->data['status'] ?? '') === 'success';
                        @endphp

                        <a
                            href="{{ $notification->data['url'] ?? '#' }}"
                            class="dropdown-item"
                            style="
                                display:flex;
                                gap:10px;
                                align-items:flex-start;
                                padding:10px 12px;
                                background:{{ $isSuccess ? '#f0fdf4' : '#fef2f2' }};
                                border-bottom:1px solid var(--line);
                            "
                        >
                            <div style="font-size:18px;line-height:1;">
                                {{ $isSuccess ? '✅' : '❌' }}
                            </div>


                            <div
                                style="
                                    display:flex;
                                    flex-direction:column;
                                    gap:2px;
                                "
                            >
                                <span style="font-weight: 700; font-size: 13px; color: {{ $isSuccess ? '#166534' : '#991b1b' }};">
                                    {{ $isSuccess ? __('messages.payment_success') : __('messages.payment_failed') }}
                                </span>

                                <span
                                    style="
                                        font-size:12px;
                                        color:#4b5563;
                                    "
                                >
                                    {{
                                        $notification->data['message']
                                        ?? 'Transaksi sebesar Rp '
                                        . number_format(
                                            $notification->data['amount'] ?? 0,
                                            0,
                                            ',',
                                            '.'
                                        )
                                    }}
                                </span>

                                <span
                                    style="
                                        font-size:10px;
                                        color:#9ca3af;
                                        margin-top:2px;
                                    "
                                >
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </a>
                    @empty

                        <div
                            class="dropdown-item"
                            style="
                                text-align:center;
                                color:#9ca3af;
                                padding:12px;
                            "
                        >
                            {{ __('messages.no_notifications') }}
                        </div>
                    @endforelse

                    <div class="dropdown-divider"></div>
                    <a
                        href="{{ route('notifications.index') }}"
                        class="dropdown-item"
                        style="
                            text-align:center;
                            justify-content:center;
                            font-weight:600;
                        "
                    >
                        {{ __('messages.view_all_notifications') }}
                    </a>
                </div>
            </details>
        </div>

        {{-- Language Switcher (Direct Toggle EN / ID) --}}
        @php
            $currentLocale = app()->getLocale();
            $nextLocale = $currentLocale === 'id' ? 'en' : 'id';
        @endphp

        <a 
            href="{{ route('lang.switch', $nextLocale) }}" 
            class="icon-button"
            title="Switch to {{ strtoupper($nextLocale) }}"
            style="
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            width: 38px; 
            height: 38px; 
            border-radius: 50%; 
            border: 2px solid currentColor; 
            font-weight: 800; 
            font-size: 13px; 
            text-decoration: none;
            letter-spacing: 0.5px;
            transition: transform 0.2s ease, background-color 0.2s ease;
        "
        >
            {{ strtoupper($currentLocale) }}
        </a>


        {{-- Profile --}}
        <div class="topbar-dropdown">
            <details class="dropdown-details">

                <summary
                    class="profile-avatar"
                    title="Profil"
                >
                    {{ strtoupper(substr(auth()->user()->name ?? 'OM', 0, 2)) }}
                </summary>

                <div class="dropdown-menu">

                    <div class="dropdown-header">
                        {{ auth()->user()->name ?? 'Odeon Manager' }}
                    </div>

                    <div class="dropdown-subtext">
                        {{ auth()->user()->email ?? 'admin@odeon.id' }}
                    </div>

                    <div class="dropdown-divider"></div>

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="dropdown-item dropdown-danger"
                        >
                            🚪 Keluar / Logout
                        </button>
                    </form>
                </div>
            </details>
        </div>
    </div>
</header>