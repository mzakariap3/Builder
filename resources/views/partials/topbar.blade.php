<header class="topbar">
    <button class="topbar-menu-button" type="button" aria-label="Buka navigasi" data-sidebar-toggle>☰</button>
    <div class="topbar-title">{{ $title ?? (request()->routeIs('reports.*') ? 'Financial Reports' : (request()->routeIs('transactions.*') ? 'Financial Data' : 'Dashboard')) }}</div>
    <form class="global-search" action="{{ route('transactions.index') }}" method="GET">
        <span>⌕</span>
        <input name="search" value="{{ request('search') }}" placeholder="Search transactions...">
    </form>
    <div class="topbar-actions">
        <button type="button" class="icon-button notification-button" title="Notifikasi" aria-label="Notifikasi"><span aria-hidden="true">♧</span><i></i></button>
        <a href="{{ route('masters.index') }}" class="icon-button" title="Settings">⚙</a>
        <div class="profile-avatar">OM</div>
    </div>
</header>
