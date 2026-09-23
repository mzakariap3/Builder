<header class="topbar">
    <button class="topbar-menu-button" type="button" aria-label="Buka navigasi" data-sidebar-toggle>☰</button>
    <div class="topbar-title">{{ $title ?? (request()->routeIs('reports.*') ? 'Financial Reports' : (request()->routeIs('transactions.*') ? 'Financial Data' : 'Dashboard')) }}</div>
    <form class="global-search" action="{{ route('transactions.index') }}" method="GET">
        <span>⌕</span>
        <input name="search" value="{{ request('search') }}" placeholder="Search transactions...">
    </form>
    <div class="topbar-actions">
        <button type="button" class="icon-button notification-button" title="Notifikasi" aria-label="Notifikasi"><span aria-hidden="true">♧</span><i></i></button>
        <div class="topbar-dropdown">
            <details class="dropdown-details">
                <summary class="icon-button" title="Settings" aria-label="Settings">⚙</summary>
                <div class="dropdown-menu">
                    <div class="dropdown-header">Pengaturan Master</div>
                    <a href="{{ route('masters.index') }}" class="dropdown-item">
                        📊 Kelola Master Data
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('masters.index') }}#places" class="dropdown-item">
                        📍 Tempat Wisata / Unit
                    </a>
                    <a href="{{ route('masters.index') }}#categories" class="dropdown-item">
                        🏷️ Kategori Transaksi
                    </a>
                    <a href="{{ route('masters.index') }}#sources" class="dropdown-item">
                        💳 Sumber Pendapatan
                    </a>
                </div>
            </details>
        </div>

        <!-- Profil & Logout -->
        <div class="topbar-dropdown">
            <details class="dropdown-details">
                <summary class="profile-avatar" title="Profil">{{ substr(auth()->user()->name ?? 'OM', 0, 2) }}</summary>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header">{{ auth()->user()->name ?? 'Odeon Manager' }}</div>
                    <div class="dropdown-subtext">{{ auth()->user()->email ?? 'admin@odeon.id' }}</div>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item dropdown-danger">
                            🚪 Keluar / Logout
                        </button>
                    </form>
                </div>
            </details>
        </div>
    </div>
</header>