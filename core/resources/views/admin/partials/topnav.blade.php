<!-- navbar-wrapper start -->
<nav class="navbar-wrapper bg--dark">
    <div class="navbar__left">
        <button type="button" class="res-sidebar-open-btn me-3"><i class="las la-bars"></i></button>
        <form class="navbar-search">
            <input type="search" name="#0" class="navbar-search-field" id="searchInput" autocomplete="off"
                placeholder="@lang('Search here...')">
            <i class="las la-search"></i>
            <ul class="search-list"></ul>
        </form>
    </div>
    <div class="navbar__right">
        <ul class="navbar__action-list">
            <li>
                <span id="current-time" class="text-white">{{ now()->format('l, d F Y h:i A') }}</span>
            </li>
            @if(can_access('view-notification'))
                <li class="dropdown">
                    <button type="button" class="primary--layer" data-bs-toggle="dropdown" data-display="static"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="las la-bell text--primary @if($adminNotificationCount > 0) icon-left-right @endif"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu--md p-0 border-0 box--shadow1 dropdown-menu-right">
                        <div class="dropdown-menu__header">
                            <span class="caption">@lang('Notification')</span>
                            @if($adminNotificationCount > 0)
                                <p>@lang('You have') {{ $adminNotificationCount }} @lang('unread notification')</p>
                            @else
                                <p>@lang('No unread notification found')</p>
                            @endif
                        </div>
                        <div class="dropdown-menu__body">
                            @foreach($adminNotifications as $notification)
                                <a href="{{ route('admin.notification.read',$notification->id) }}"
                                    class="dropdown-menu__item">
                                    <div class="navbar-notifi">
                                        <div class="navbar-notifi__right">
                                            <h6 class="notifi__title">{{ __($notification->title) }}</h6>
                                            <span class="time"><i class="far fa-clock"></i>
                                                {{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div><!-- navbar-notifi end -->
                                </a>
                            @endforeach
                        </div>
                        <div class="dropdown-menu__footer">
                            <a href="{{ route('admin.notifications') }}"
                                class="view-all-message">@lang('View all notification')</a>
                        </div>
                    </div>
                </li>
            @endif
            <li class="dropdown">
                <button type="button" class="" data-bs-toggle="dropdown" data-display="static" aria-haspopup="true"
                    aria-expanded="false">
                    <span class="navbar-user">
                        <span class="navbar-user__thumb"><img
                                src="{{ getImage('assets/admin/images/profile/'. auth()->guard('admin')->user()->image) }}"
                                alt="image"></span>
                        <span class="navbar-user__info">
                            <span
                                class="navbar-user__name">{{ auth()->guard('admin')->user()->username }}</span>
                        </span>
                        <span class="icon"><i class="las la-chevron-circle-down"></i></span>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu--sm p-0 border-0 box--shadow1 dropdown-menu-right">
                    <a href="{{ route('admin.profile') }}"
                        class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                        <i class="dropdown-menu__icon las la-user-circle"></i>
                        <span class="dropdown-menu__caption">@lang('Profile')</span>
                    </a>
                    
                    @if (auth()->guard('admin')->user()->id == 1 || auth()->guard('admin')->user()->id == 2)
                        <a href="{{ route('admin.password') }}"
                            class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                            <i class="dropdown-menu__icon las la-key"></i>
                            <span class="dropdown-menu__caption">@lang('Password')</span>
                        </a>
                    @endif

                    <a href="{{ route('admin.logout') }}"
                        class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                        <i class="dropdown-menu__icon las la-sign-out-alt"></i>
                        <span class="dropdown-menu__caption">@lang('Logout')</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- navbar-wrapper end -->
@push('script')
<script>
    function updateTime() {
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            hour12: true
        };

        const now = new Date();
        const formattedTime = now.toLocaleDateString('en-US', options);
        $('#current-time').text(formattedTime);
    }

    // Update time immediately on page load
    updateTime();

    // Update time every second
    setInterval(updateTime, 1000);
</script>
@endpush
