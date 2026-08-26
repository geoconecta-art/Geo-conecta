<div class="nk-sidebar nk-sidebar-fixed is-light" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head bg-white">
        <div class="nk-menu-trigger">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu">
                <em class="icon ni ni-arrow-left"></em>
            </a>

            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex compact-active"
                data-target="sidebarMenu">
                <em class="icon ni ni-menu"></em>
            </a>
        </div>

        <div class="nk-sidebar-brand w-100 d-flex justify-content-center pe-2">
            <img src="{{asset('assets/images/geoconecta-logo.jpg')}}" class="mr-1" alt="logo" style="width:130px;">
        </div>
    </div>

    <div class="nk-sidebar-element nk-sidebar-body">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu simplebar-scrollable-y simplebar-scrollable-x" data-simplebar="init">
                <div class="simplebar-wrapper" style="margin: -16px 0px -40px;">
                    <div class="simplebar-height-auto-observer-wrapper">
                        <div class="simplebar-height-auto-observer"></div>
                    </div>

                    <div class="simplebar-mask">
                        <div class="simplebar-offset d-flex" style="right: 0px; bottom: 0px;">
                            @include('admin.geovisor._sidebar_nav')
                            @include('admin.geovisor._sidebar_plans')
                        </div>
                    </div>
                    <div class="simplebar-placeholder" style="width: 73px; height: 1634px;"></div>
                </div>
                
            </div>
        </div>
    </div>
</div>
