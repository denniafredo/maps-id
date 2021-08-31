<!-- Top navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block"> 
            <span style="font-weight: bold; font-size: 25px; color: black;">@yield('title')</span>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <span><strong>{{ Auth::user()->name }} ({{ Auth::user()->role->role }})</strong></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="padding: 10px;">
            <a href="{{ route('logout') }} class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="padding: 10px;">
                <i class="fa fa-lock" style="padding: 5px;"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
        </li>
    </ul>
</nav>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('cms/overview') }}" class="brand-link">
        <i class="nav-icon fas fa-map-marker-alt" style="padding-left: 23px; padding-right: 5px;"></i>
        <span class="brand-text font-weight-light">Dashboard Kit</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('cms/overview') }}" class="nav-link">
                    <i class="nav-icon fas fa-chart-pie"></i>
                    <p>
                        Overview
                    </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('cms/fauna/create') }}" class="nav-link">
                    <i class="nav-icon fas fa-plus-circle"></i>
                    <p>
                        Tambah Data
                    </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('cms/kml/upload') }}" class="nav-link">
                    <i class="nav-icon fas fa-file"></i>
                    <p>
                        Upload KML
                    </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('cms/map/edit') }}" class="nav-link">
                    <i class="nav-icon fas fa-map"></i>
                    <p>
                        Ubah Titik Tengah Peta
                    </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('cms/user') }}" class="nav-link">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                        Kelola User
                    </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Master
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('cms/master/province') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Provinsi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('cms/master/conservation-status') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Status Konservasi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('cms/master/kingdom') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Kingdom</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('cms/master/phylum') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Filum</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('cms/master/class') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Kelas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('cms/master/ordo') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Ordo</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('cms/master/family') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Famili</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('cms/master/genus') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Genus</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>