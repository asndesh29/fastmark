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
                    <a class="nav-link menu-link" href="#">
                        <i class="ri-honour-line"></i> <span data-key="t-widgets">Dashboard</span>
                    </a>
                </li>

                <!-- Renewal -->
                {{-- <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.renewal.index') }}">
                        <i class="ri-honour-line"></i> <span data-key="t-widgets">Renew</span>
                    </a>
                </li> --}}
                <!-- Renewal -->

                <!-- Customers -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::is('admin/customer/*') ? 'active' : '' }}" href="#sidebarCustomer" data-bs-toggle="collapse" role="button" aria-expanded="{{ Route::is('admin.customer.*') ? 'true' : 'false' }}"  aria-controls="sidebarCustomer">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Customer</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.customer.*') ? 'show' : '' }}" id="sidebarCustomer">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.customer.create') }}" class="nav-link {{ Request::is('admin/customer/add-new') ? 'active' : '' }}" data-key="t-analytics"> Add New </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.customer.index') }}" class="nav-link {{ Request::is('admin/customer') ? 'active' : '' }}" data-key="t-analytics"> List </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- Customers -->

                <!-- Vehicles -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::is('admin/vehicle/*') ? 'active' : '' }}" href="#sidebarVehicle" data-bs-toggle="collapse" role="button" aria-expanded="{{ Route::is('admin.vehicle.*') ? 'true' : 'false' }}"  aria-controls="sidebarVehicle">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Vehicle</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.vehicle.*') ? 'show' : '' }}" id="sidebarVehicle">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.vehicle.index') }}" class="nav-link {{ Request::is('admin/vehicle') ? 'active' : '' }}" data-key="t-analytics"> List </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- Vehicles -->

                <!-- Renewal -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::is('admin/renewal/*') ? 'active' : '' }}" href="#sidebarRenewal" data-bs-toggle="collapse" role="button" aria-expanded="{{ Route::is('admin.renewal.*') ? 'true' : 'false' }}"  aria-controls="sidebarRenewal">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Renewal</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.renewal.*') ? 'show' : '' }}" id="sidebarRenewal">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.renewal.bluebook.index') }}" class="nav-link {{ Request::is('admin/renewal/bluebook') ? 'active' : '' }}" data-key="t-analytics"> Bluebook </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('admin.renewal.checkpass.index') }}" class="nav-link {{ Request::is('admin/renewal/checkpass') ? 'active' : '' }}" data-key="t-analytics"> Jach Pass </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.renewal.insurance.index') }}" class="nav-link {{ Request::is('admin/renewal/insurance') ? 'active' : '' }}" data-key="t-analytics"> Insurance </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="{{ route('admin.license.index') }}" class="nav-link" data-key="t-analytics"> License </a>
                            </li> --}}
                            <li class="nav-item">
                                <a href="{{ route('admin.renewal.pollution.index') }}" class="nav-link {{ Request::is('admin/renewal/pollution') ? 'active' : '' }}" data-key="t-analytics"> Pollution </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.renewal.road-permit.index') }}" class="nav-link {{ Request::is('admin/renewal/road-permit') ? 'active' : '' }}" data-key="t-analytics"> Road Permit </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.renewal.vehicle-tax.index') }}" class="nav-link {{ Request::is('admin/renewal/vehicle-tax') ? 'active' : '' }}" data-key="t-analytics"> Vehicle Tax </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- Renewal -->

                <!-- Settings -->
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Setting</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ Request::is('admin/settings/*') ? 'active' : '' }}" href="#sidebarSettings" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ Route::is('admin.settings.*') ? 'true' : 'false' }}" aria-controls="sidebarSettings">
                        <i class="ri-pencil-ruler-2-line"></i> <span data-key="t-base-ui">Settings</span>
                    </a>
                    <div class="collapse menu-dropdown {{ Route::is('admin.settings.*') ? 'show' : '' }}" id="sidebarSettings">
                        <ul class="nav nav-sm flex-column">
                            {{-- <li class="nav-item">
                                <a href="{{ route('admin.settings.feeslab.index') }}" class="nav-link {{ Request::is('admin/settings/feeslab/list') ? 'active' : '' }}" data-key="t-analytics"> Fee Slabs </a>
                            </li>

                            <li class="nav-item">
                                <a href="" class="nav-link" data-key="t-analytics"> License Type </a>
                            </li> --}}

                            <li class="nav-item">
                                <a href="{{ route('admin.settings.renewal-type.index') }}" 
                                    class="nav-link {{ Request::is('admin/settings/renewal-type/list') ? 'active' : '' }}" 
                                    data-key="t-analytics"> Renewal Type </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.settings.vehicle.category.index') }}" 
                                    class="nav-link {{ Request::is('admin/settings/vehicle/category/list') ? 'active' : '' }}" 
                                    data-key="t-analytics"> Vehicle Category </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.settings.vehicle.type.index') }}" 
                                    class="nav-link {{ Request::is('admin/settings/vehicle/type/list') ? 'active' : '' }}" 
                                    data-key="t-analytics"> Vehicle Type </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.settings.insurance-provider.index') }}" 
                                    class="nav-link {{ Request::is('admin/settings/insurance-provider/list') ? 'active' : '' }}" 
                                    data-key="t-analytics"> Insurance Provider </a>
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