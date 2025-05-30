<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <div class="sidebar-brand d-flex align-items-center justify-content-center">
        <div class="sidebar-brand-text mx-3">SPK Siswa Dengan AHP</div>
    </div>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.admin') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Kriteria -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('kriteria.index.admin') }}">
            <i class="fas fa-fw fa-database"></i>
            <span>Kriteria</span>
        </a>
    </li>

    <!-- Alternatif -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('alternatif.index.admin') }}">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Alternatif</span>
        </a>
    </li>

    <!-- Perbandingan Kriteria -->
    <li class="nav-item">
        <a class="nav-link" href="{{  route('perbandingan-kriteria.index.admin') }}">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Perbandingan Kriteria</span>
        </a>
    </li>

    <!-- Settings -->
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-cogs"></i>
            <span>Settings</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
