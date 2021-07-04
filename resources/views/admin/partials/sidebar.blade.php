<div class="vertical-menu">
    <div class="navbar-brand-box">
        <a href="{{ url('admin') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets/admin/images/favicon.png') }}" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/admin/images/logo.png') }}" alt="" height="35">
            </span>
        </a>
        <a href="{{ url('admin') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('assets/admin/images/favicon.png') }}" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/admin/images/logo.png') }}" alt="" height="35">
            </span>
        </a>
    </div>
    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>
    <div data-simplebar class="sidebar-menu-scroll">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">{{ __('Menu') }}</li>
                <li>
                    <a href="{{ url('admin') }}" class="waves-effect">
                        <i class="uil-home-alt"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.money-transfer.create') }}" class="waves-effect">
                        <i class="uil-money-withdrawal"></i>
                        <span>Money Transfer</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>