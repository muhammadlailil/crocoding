<th>
    <div class="main-navbar sticky-top bg-white">
        <!-- Main Navbar -->
        <nav class="navbar align-items-stretch navbar-light flex-md-nowrap p-0">
            <div action="#" class="main-navbar__search w-100 d-none d-md-flex d-lg-flex"> </div>
            <ul class="navbar-nav flex-row ">
                <li class="nav-item  dropdown notifications">
                    <a class="nav-link nav-link-icon text-center" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="nav-link-icon__wrapper">
                            <i class="material-icons">&#xE7F4;</i>
                            <span class="badge badge-pill badge-danger">0</span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-small" aria-labelledby="dropdownMenuLink">
                        {{--                        <a class="dropdown-item" href="#">--}}
{{--                            <div class="notification__content">--}}
{{--                                <p>This is notification sample</p>--}}
{{--                            </div>--}}
{{--                        </a>--}}
                        <a class="text-center dropdown-item" href="#" style="display: block">
                            <div class="notification__content">
                                <p class="text-center">You don't have notification yet</p>
                            </div>
                        </a>

                        <a class="dropdown-item notification__all text-center" href="#"> View all Notifications </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-nowrap px-3" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="margin-right: 50px">
                        <div class="imgProfilUsers" style="background-image: url('{{getSession('admin_photo')}}')"></div>
                        <span class="d-none d-md-inline-block" style="margin-top: 8px">{{getSession('admin_name')}}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-small">
                        <a class="dropdown-item" href="{{ Route('Admin\AdminCmsUsersControllerGetProfile') }}">
                            <i class="material-icons">&#xE7FD;</i> Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('getLockScreen') }}">
                            <i class='fa fa-key'></i> Lock Screen
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="javascript:;" onclick="Logout('{{ route("getLogout") }}')" style="margin-bottom: 5px">
                            <i class="material-icons text-danger">&#xE879;</i> Logout </a>
                    </div>
                </li>
            </ul>
            <nav class="nav">
                <a href="#" class="nav-link nav-link-icon toggle-sidebar d-md-inline d-lg-none text-center border-left" data-toggle="collapse" data-target=".header-navbar" aria-expanded="false" aria-controls="header-navbar">
                    <i class="material-icons">&#xE5D2;</i>
                </a>
            </nav>
        </nav>
    </div>
</th>
