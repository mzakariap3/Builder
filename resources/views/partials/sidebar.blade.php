<aside class="sidebar">
    <div class="brand-block">
        <img src="{{ asset('assets/odeon-logo.jpg') }}" alt="Odeon" class="brand-logo">
        <div>
            <div class="brand-name">Odeon<br>Management</div>
            <div class="brand-sub">Financial Oversight</div>
        </div>
    </div>

    <nav class="nav-menu">
        <a class="nav-item {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ ($admin ?? false) ? route('admin.dashboard') : route('dashboard') }}">
            <span class="nav-icon">▦</span><span>Dashboard</span>
        </a>
        <a class="nav-item {{ request()->routeIs('transactions.create') ? 'active' : '' }}" href="{{ route('transactions.create') }}">
            <span class="nav-icon">⊞</span><span>Add Entry</span>
        </a>
        <a class="nav-item {{ request()->routeIs('transactions.index') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
            <span class="nav-icon">▤</span><span>Transactions</span>
        </a>
        <a class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
            <span class="nav-icon">▥</span><span>Reports</span>
        </a>
    </nav>
</aside>
