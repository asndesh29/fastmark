<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="assets/images/logo-dark.png" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="assets/images/logo-light.png" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>
    
    <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <img class="rounded header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                <span class="text-start">
                    <span class="d-block fw-medium sidebar-user-name-text">Anna Adame</span>
                    <span class="d-block fs-14 sidebar-user-name-sub-text"><i class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span class="align-middle">Online</span></span>
                </span>
            </span>
        </button>

        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <h6 class="dropdown-header">Welcome Anna!</h6>
            <a class="dropdown-item" href="pages-profile.html"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="auth-logout-basic.html"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
        </div>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="widgets.html">
                        <i class="ri-honour-line"></i> <span data-key="t-widgets">Dashboard</span>
                    </a>
                </li>

                <!-- Renewal -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarRenew" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarRenew">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Renew</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarRenew">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.renewal.road_permit.create') }}" class="nav-link" data-key="t-analytics"> Road Permit </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.renewal.pollution_check.create') }}" class="nav-link" data-key="t-analytics"> Pollution </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.renewal.check_pass.create') }}" class="nav-link" data-key="t-analytics"> Check Pass </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.renewal.bluebook.create') }}" class="nav-link" data-key="t-analytics"> Blue Book </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.renewal.insurance.create') }}" class="nav-link" data-key="t-analytics"> Insurance </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.renewal.tax.create') }}" class="nav-link" data-key="t-analytics"> Vehicle Tax </a>
                            </li>

                            <li class="nav-item">
                                <a href="" class="nav-link" data-key="t-analytics"> License </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- Renewal -->

                <!-- Customers -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarCustomers" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCustomers">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Customers</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarCustomers">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.customer.create') }}" class="nav-link" data-key="t-analytics"> Add New </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.customer.index') }}" class="nav-link" data-key="t-analytics"> List </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- Customers -->

                <!-- Vehicles -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarVehicle" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarVehicle">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Vehicle</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarVehicle">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.feeslab.index') }}" class="nav-link" data-key="t-analytics"> List </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- Vehicles -->

                <!-- Settings -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarSettings" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSettings">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Settings</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarSettings">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.feeslab.index') }}" class="nav-link" data-key="t-analytics"> Fee Slabs </a>
                            </li>

                            <li class="nav-item">
                                <a href="" class="nav-link" data-key="t-analytics"> License Type </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.renewal.type.index') }}" class="nav-link" data-key="t-analytics"> Renewal Type </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.vehicle.category.index') }}" class="nav-link" data-key="t-analytics"> Vehicle Category </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.vehicle.type.index') }}" class="nav-link" data-key="t-analytics"> Vehicle Type </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- Settings -->

                
                <!-- end Dashboard Menu -->
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->