<header class="main-header">
    <div class="header-left">
        <h2>{{ $title ?? 'Dashboard' }}</h2>
        <nav class="nav-tabs">
            <a href="{{ url('/dashboard') }}" class="nav-tab {{ request()->is('dashboard') ? 'active' : '' }}">Employees</a>
            @if(auth()->check() && auth()->user()->isAdmin())
            <a href="{{ url('/departments') }}" class="nav-tab {{ request()->is('departments') ? 'active' : '' }}">Departments</a>
            @endif
            <a href="{{ url('/roles') }}" class="nav-tab {{ request()->is('roles') ? 'active' : '' }}">RBAC</a>
        </nav>
    </div>
    <div class="header-right">
        <span>Welcome, {{ auth()->user()->name }}</span>
        <a class="logout" style="cursor: pointer;">| Logout</a>
    </div>
</header>
