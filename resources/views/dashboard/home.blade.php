@extends('dashboard.layouts.app')

@section('content')

    <div class="content-wrapper" style="min-height: 0">

        <section class="content-header">

            <h1>@lang('site.dashboard')</h1>

            <ol class="breadcrumb">
                <li class="active"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</li>
            </ol>
        </section>

        <section class="content">

            <div class="row">

                {{-- categories --}}
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{count(App\Models\Employees::get())}}</h3>

                            <p>@lang('site.employees')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div> 
                        @if (auth()->user()->hasPermission('read-employees'))
                            <a href="{{ route('dashboard.employees.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{count(App\Models\Employer::get())}}</h3>

                            <p>@lang('site.employers')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        @if (auth()->user()->hasPermission('read-employers'))
                            <a href="{{ route('dashboard.employers.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{count(App\Models\job::get())}}</h3>

                            <p>@lang('site.jobs')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        @if (auth()->user()->hasPermission('read-jobs'))
                            <a href="{{ route('dashboard.jobs.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                        @endif                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{count(App\Models\EmployeeJob::get())}}</h3>

                            <p>@lang('site.employeejobs')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        @if (auth()->user()->hasPermission('read-employeejobs'))
                            <a href="{{ route('dashboard.employeejobs.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                        @endif                    </div>
                </div>

            </div><!-- end of row -->
            <div class="row">

                {{-- categories --}}
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{count(App\User::get())}}</h3>

                            <p>@lang('site.users')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div> 
                        @if (auth()->user()->hasPermission('read-users'))
                            <a href="{{ route('dashboard.users.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-black">
                        <div class="inner">
                            <h3>{{count(App\Role::get())}}</h3>

                            <p>@lang('site.roles')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        @if (auth()->user()->hasPermission('read-roles'))
                            <a href="{{ route('dashboard.roles.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{count(App\Models\Category::get())}}</h3>

                            <p>@lang('site.categories')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        @if (auth()->user()->hasPermission('read-categories'))
                            <a href="{{ route('dashboard.categories.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                        @endif                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{count(App\Models\Country::get())}}</h3>

                            <p>@lang('site.countries')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        @if (auth()->user()->hasPermission('read-countries'))
                            <a href="{{ route('dashboard.countries.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                        @endif                    </div>
                </div>

            </div><!-- end of row -->
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{count(App\Models\City::get())}}</h3>

                            <p>@lang('site.cities')</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        @if (auth()->user()->hasPermission('read-cities'))
                            <a href="{{ route('dashboard.cities.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                        @endif                    </div>
                </div>
            </div>
        </section><!-- end of content -->
        {{-- @include('dashboard.layouts._char') --}}

    </div><!-- end of content wrapper -->


@endsection


@push('script')


@endpush
