<!-- Main Sidebar -->
<aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
    <div class="main-navbar">
        <nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap p-0">
            <a class="navbar-brand w-100 mr-0" href="{{\crocodicstudio\crocoding\helpers\Crocoding::adminPath()}}" style="line-height: 25px;">
                <div class="d-table m-auto">
                    <img id="main-logo" class="d-inline-block align-top mr-1" style="max-width: 119px;"
                         src="{{getSession('applogo')}}" alt="{{getSession('appname')}}">
                </div>
            </a>
            <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                <i class="material-icons">&#xE5C4;</i>
            </a>
        </nav>
    </div>
    <form action="#" class="main-sidebar__search w-100 border-right d-sm-flex d-md-none d-lg-none">
        <div class="input-group input-group-seamless ml-3">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <input class="navbar-search form-control" type="text" placeholder="Search for something..." aria-label="Search"></div>
    </form>
    <div class="nav-wrapper">
        <?php $dashboard = \crocodicstudio\crocoding\helpers\Crocoding::sidebarDashboard();?>
        @if($dashboard)
            <ul data-id='{{$dashboard->id}}' class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ (requestIs(config('crocoding.ADMIN_PATH'))) ? 'active nav-link' : 'nav-link' }}"
                       href="{{\crocodicstudio\crocoding\helpers\Crocoding::adminPath()}}"
                    >
                        <i class="material-icons">dashboard</i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        @endif
        @foreach(\crocodicstudio\crocoding\helpers\Crocoding::sidebarMenu() as $menu)
            <ul data-id='{{$menu->id}}' class="nav flex-column">
                <li class="nav-item">
                    @if(empty($menu->children))
                        <a class="nav-link {{ (requestIs($menu->url_path.'*')) ? 'active nav-link' : 'nav-link' }}"
                           href="{{ ($menu->is_broken)?"javascript:alert('Controller / Route Not Found')":$menu->url }}"
                        >
                            <i class="{{$menu->icon}} {{($menu->color)?"text-".$menu->color:""}}"></i>
                            <span>{{$menu->name}}</span>
                        </a>
                    @else
                        <a class="nav-link dropdown-toggle "
                           data-toggle="dropdown"
                           href="javascript:;" role="button"
                           aria-haspopup="false"
                           aria-expanded="false"
                        >
                            <i class="{{$menu->icon}} {{($menu->color)?"text-".$menu->color:""}}"></i>
                            <span>{{$menu->name}}</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-small" x-placement="bottom-start"
                        >
                            @foreach($menu->children as $child)
                            <a class="dropdown-item {{ (requestIs($child->url_path .= !ends_with(request()->decodedPath(), $child->url_path) ? "/*" : "")) ? 'active' : '' }}"
                               data-id='{{$child->id}}'
                               href="{{ ($child->is_broken)?"javascript:alert('Controller / Route Not Found')":$child->url}}"
                            >
                                <span>{{$child->name}}</span>
                            </a>
                            @endforeach
                        </div>
                    @endif
                </li>
            </ul>
        @endforeach

        @if(\crocodicstudio\crocoding\helpers\Crocoding::isSuperadmin())
            <ul style="padding-left: 0px">

                <h6 class="main-sidebar__nav-title">Super Admin</h6>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="{{ (requestIs(config('crocoding.ADMIN_PATH').'/privileges*')) ? 'active nav-link' : 'nav-link' }}"
                           href="{{Route("PrivilegesControllerGetIndex")}}"
                        >
                            <i class="fa fa-key"></i>
                            <span>Privileges Roles</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="{{ (requestIs(config('crocoding.ADMIN_PATH').'/users*')) ? 'active nav-link' : 'nav-link' }}"
                           href="{{Route("Admin\AdminCmsUsersControllerGetIndex")}}"
                        >
                            <i class="fa fa-users"></i>
                            <span>Users Management</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="{{ (requestIs(config('crocoding.ADMIN_PATH').'/menu_management*')) ? 'active nav-link' : 'nav-link' }}"
                           href="{{Route("MenusControllerGetIndex")}}"
                        >
                            <i class="fa fa-bars"></i>
                            <span>Menu Management</span>
                        </a>
                    </li>

                    <li class="nav-item">

                        <!-- jika dropdown -->
                        <a class="nav-link dropdown-toggle {{ (requestIs(config('crocoding.ADMIN_PATH').'/settings/*')) ? 'active' : '' }}"
                           data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"
                        >
                            <i class="fa fa-wrench"></i>
                            <span>Setting</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-small" x-placement="bottom-start"

                        >
                            <a class="dropdown-item {{ (requestIs(config('crocoding.ADMIN_PATH').'/settings*')) ? 'active' : '' }}"
                               href="{{Route('SettingsControllerGetAdd')}}"
                            >
                                <span>Add New Setting</span>
                            </a>

                            <?php
                            $groupSetting = DB::table('cms_settings')->groupby('group_setting')->pluck('group_setting');
                            foreach ($groupSetting as $gs):
                            ?>
                            <a class="dropdown-item <?=($gs == g('group')) ? 'active' : ''?>"
                               href='{{route("SettingsControllerGetShow")}}?group={{urlencode($gs)}}&m=0'
                            >
                                <span>{{$gs}}</span>
                            </a>
                            <?php endforeach;?>

                        </div>

                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ (requestIs(config('crocoding.ADMIN_PATH').'/module_generator')) ? 'active' : '' }}"
                           href='{{Route("ModulsControllerGetIndex")}}'
                        >
                            <i class="fa fa-th"></i>
                            <span>Module Generator</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ (requestIs(config('crocoding.ADMIN_PATH').'/api_generator*')) ? 'active' : '' }}"
                           href="{{Route('ApiCustomControllerGetIndex')}}"
                        >
                            <i class="fa fa-fire"></i>
                            <span>Api Generator</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ (requestIs(config('crocoding.ADMIN_PATH').'/email_templates*')) ? 'active' : '' }}"
                           href="{{Route('EmailTemplatesControllerGetIndex')}}"
                        >
                            <i class="fa fa-envelope"></i>
                            <span>Email Template</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ (requestIs(config('crocoding.ADMIN_PATH').'/logs*')) ? 'active' : '' }}"
                           href="{{Route('LogsControllerGetIndex')}}"
                        >
                            <i class="fa fa-flag"></i>
                            <span>Log User Access</span>
                        </a>
                    </li>
                </ul>
            </ul>
        @endif
    </div>
</aside>
<!-- End Main Sidebar -->
