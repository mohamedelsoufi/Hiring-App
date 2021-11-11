@extends('dashboard.layouts.app')

@section('title', __('site.' . $module_name_plural))


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.'.$module_name_plural)</h1>

            <ol class="breadcrumb">
                <li> <a href="{{ route('dashboard.home') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a>
                </li>
                <li class="active"><i class="fa fa-briefcase"></i> @lang('site.'.$module_name_plural)</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <h1 class="box-title"> @lang('site.'.$module_name_plural) <small>{{ $rows->total() }}</small></h1>

                    <form action="{{ route('dashboard.' . $module_name_plural . '.index') }}" method="get">

                        <div class="row">

                            <div class="col-md-4">
                                <input type="text" name="search" value="{{ request()->search }}" class="form-control"
                                    placeholder="@lang('site.search')">
                            </div>

                            <div class="col-md-4">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>
                                    @lang('site.search')</button>
                                    @if (auth()->user()->hasPermission('create-'.$module_name_plural))
                                    <a href="{{ route('dashboard.' . $module_name_plural . '.create') }}"
                                    class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                    @else
                                        <button class="btn btn-primary btn"disabled><i class="fa fa-plus"></i>add </button>
                                    @endif


                            </div>
                        </div>
                    </form>
                </div> {{-- end of box header --}}

                <div class="box-body">

                    @if ($rows->count() > 0)

                    <table class="table table-hover">

                        <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>@lang('site.title1')</th>
                                    <th>@lang('site.category_id')</th>
                                    <th>@lang('site.details')</th>
                                    <th>@lang('site.getAllCandits')</th>
                                    <th>@lang('site.getAcceptCandits')</th>
                                    <th>@lang('site.getRejectCandits')</th>
                                    <th>@lang('site.getNotConfirmCandits')</th>
                                    <th>@lang('site.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($rows as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $row->title }}</td>
                                        <td>{{ $row->category->name }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal{{$row->id}}">@lang('site.avmeeting')</button>
                                            @include('dashboard.jobs.avmeeting')
                                        </td>
                                        <th><a href="{{route('dashboard.getAllCandits',$row->id)}}">{{$row->EmployeeJob->count()}}</a></th>
                                        <th><a href="{{route('dashboard.getAcceptCandits',$row->id)}}">{{$row->EmployeeJob->where('candat_applay_status',1)->count()}}</a></th>
                                        <th><a href="{{route('dashboard.getRejectCandits',$row->id)}}">{{$row->EmployeeJob->where('candat_applay_status',0)->count()}}</a></th>
                                        <th><a href="{{route('dashboard.getNotConfirmCandits',$row->id)}}">{{$row->EmployeeJob->where('candat_applay_status',2)->count()}}</a></th>
                                        
                                        <td>
                                            @if (auth()->user()->hasPermission('update-'.$module_name_plural))
                                                @include('dashboard.buttons.edit')
                                            @else
                                            <button class="btn btn-info btn-sm"type="submit" value="" disabled>
                                                <i class="fa fa-edit">edit</i>
                                                </button>
                                            @endif

                                            @if (auth()->user()->hasPermission('delete-'.$module_name_plural))
                                            @include('dashboard.buttons.delete')
                                        @else
                                           <button class="btn btn-danger btn-sm"type="submit" value="edit" disabled>
                                                Delete<i class="fa fa-trash"></i>
                                                </button>
                                        @endif

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table> {{-- end of table --}}

                        {{ $rows->appends(request()->query())->links() }}

                    @else
                        <tr>
                            <h4>@lang('site.no_records')</h4>
                        </tr>
                    @endif

                </div> {{-- end of box body --}}

            </div> {{-- end of box --}}

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
