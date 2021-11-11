<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{auth()->user()->image_path}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{auth()->user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> @lang('site.statue')</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">

            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/home')? 'active':''}}"><a href="{{ route('dashboard.home') }}"><i
                        class="fa fa-dashboard"></i><span>@lang('site.dashboard')</span></a></li>

           
            @if (auth()->user()->hasPermission('read-users'))
            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/users*')? 'active':''}}"><a href="{{ route('dashboard.users.index') }}"><i
                        class="fa fa-users"></i><span>@lang('site.users')</span></a></li>
            @endif

            @if (auth()->user()->can('read-roles'))
                   <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/roles*')? 'active':''}}"><a href="{{ route('dashboard.roles.index') }}"><i
                        class="fa fa-hourglass-half"></i><span>@lang('site.roles')</span></a></li>
            @endif
            @if (auth()->user()->hasPermission('read-categories'))
            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/categories*')? 'active':''}}"><a href="{{ route('dashboard.categories.index') }}"><i
                        class="fa fa-tasks"></i><span>@lang('site.categories')</span></a></li>
            @endif
            @if (auth()->user()->hasPermission('read-countries'))
            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/countries*')? 'active':''}}"><a href="{{ route('dashboard.countries.index') }}"><i
                        class="fa fa-globe"></i><span>@lang('site.countries')</span></a></li>
            @endif
            @if (auth()->user()->hasPermission('read-cities'))
            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/cities*')? 'active':''}}"><a href="{{ route('dashboard.cities.index') }}"><i
                        class="fa fa-flag"></i><span>@lang('site.cities')</span></a></li>
            @endif
            @if (auth()->user()->hasPermission('read-employees'))
            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/employees*')|| request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/allAcceptCandits*') || request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/allCandits*') || request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/allRejectCandits*') || request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/allNotConfirmCandits*')? 'active':''}}"><a href="{{ route('dashboard.employees.index') }}"><i
                        class="fa fa-user-circle-o"></i><span>@lang('site.employees')</span></a></li>
            @endif
            @if (auth()->user()->hasPermission('read-employers'))
            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/employers*')? 'active':''}}"><a href="{{ route('dashboard.employers.index') }}"><i
                        class="fa fa-building"></i><span>@lang('site.employers')</span></a></li>
            @endif
            @if (auth()->user()->hasPermission('read-jobs'))
            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/jobs*') || request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/myjobs*') || request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/MyEnrollJobs*') ? 'active':''}}"><a href="{{ route('dashboard.jobs.index') }}"><i
                        class="fa fa-briefcase"></i><span>@lang('site.jobs')</span></a></li>
            @endif
            @if (auth()->user()->hasPermission('read-employeejobs'))
            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/employeejobs*')? 'active':''}}"><a href="{{ route('dashboard.employeejobs.index') }}"><i
                        class="fa fa-book"></i><span>@lang('site.employeejobs')</span></a></li>
            @endif
            @if (auth()->user()->hasPermission('read-ads'))
            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/ads*')? 'active':''}}"><a href="{{ route('dashboard.ads.index') }}"><i
                        class="fa fa-video-camera"></i><span>@lang('site.ads')</span></a></li>
            @endif





        </ul>

    </section>

</aside>
