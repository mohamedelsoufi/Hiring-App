@extends('dashboard.layouts.app')

@section('title', __('site.' . $module_name_plural))


@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.'.$module_name_plural)</h1>

            <ol class="breadcrumb">
                <li> <a href="{{ route('dashboard.home') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a>
                </li>
                <li class="active"><i class="fa fa-user-circle-o"></i> @lang('site.'.$module_name_plural)</li>
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
                                    <th>@lang('site.fullName')</th>
                                    <th>@lang('site.email')</th>
                                    <th>@lang('site.university')</th>
                                    <th>@lang('site.graduation_year')</th>
                                    <th>@lang('site.MyEnrollJobs')</th>
                                    <th>@lang('site.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($rows as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $row->fullName }}</td>
                                        <td>{{ $row->email }}</td>
                                        <td>{{ $row->university }}</td>
                                        <td>{{ $row->graduation_year}}</td>
                                        <td><a href="{{route('dashboard.MyEnrollJobs',$row->id)}}">{{$row->EmployeeJob->count()}}</a></td>

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
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal{{$row->id}}">@lang('site.details')</button>
                                            <!-- start popoup -->
                                            <div id="myModal{{$row->id}}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                    <div class="modal-header bg-info">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">{{$row->fullName}}</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- start conetent -->
                                                        <div class="panel card-primary">
                                                            <!-- <div class="card-header">
                                                                <h3 class="card-title">Ribbons</h3>
                                                            </div> -->
                                                            <!-- /.card-header -->
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="position-relative p-3 bg-gray" style="height: 180px">
                                                                        <div class="ribbon-wrapper">
                                                                            <div class="ribbon bg-primary" style="padding:5px 5px">
                                                                                @lang('site.video')
                                                                            </div>
                                                                        </div>
                                                                        <video style='width:100%;height:100%;' controls>
                                                                            <source src="{{$row->video_path}}" type="video/mp4">
                                                                            Your browser does not support the video tag.
                                                                        </video>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="position-relative p-3 bg-gray" style="height: 180px">
                                                                            <div class="ribbon-wrapper ribbon-lg">
                                                                                <div class="ribbon bg-primary" style="padding:5px 5px">
                                                                                    @lang('site.image')
                                                                                </div>
                                                                            </div>
                                                                            <img src="{{$row->image_path}}" alt="no image" style='width:100%;height:100%'>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="position-relative p-3 bg-gray" style="height: 180px">
                                                                        <div class="ribbon-wrapper">
                                                                            <div class="ribbon bg-primary" style="padding:5px 5px">
                                                                                @lang('site.audio')
                                                                            </div>
                                                                        </div>
                                                                        <audio controls style='width:100%;height:55%;'>
                                                                            <source src="{{$row->audio_path}}" type="audio/ogg">
                                                                        </audio>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="position-relative p-3 bg-gray" style="height: 180px">
                                                                            <div class="ribbon-wrapper ribbon-lg">
                                                                                <div class="ribbon bg-primary" style="padding:5px 5px">
                                                                                    @lang('site.cv')
                                                                                </div>
                                                                            </div>
                                                                            <a  class='btn btn-block btn-primary btn-lg' 
                                                                                href="{{$row->cv_path}}" 
                                                                                style="width: 70%;margin: auto;margin-top: 20%;"
                                                                                download>@lang('site.download')
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- /.card-body -->
                                                        </div>
                                                        <!-- end content -->
                                                    </div>
`                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('site.close')</button>
                                                    </div>
                                                    </div>

                                                </div>
                                                </div>
                                            <!-- end popup -->
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
