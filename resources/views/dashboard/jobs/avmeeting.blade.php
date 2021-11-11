<!-- start popoup -->
<div id="myModal{{$row->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header bg-info">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 class="card-title">Meeting Time</h3>
        </div>
        <div class="modal-body">
            <!-- start conetent -->
            <div class="panel card-primary">
                <div class="card-header">

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>@lang('site.from')</th>
                                        <th>@lang('site.to')</th>
                                        <th>@lang('site.status')</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach (\App\Models\Avmeeting::where('job_id',$row->id)->get() as $index => $av)
                                        <tr>
                                            <td><span style='border:1px solid black;'>{{ $av->time_from }}</span></td>
                                            <td><span style='border:1px solid black;'>{{ $av->time_to }}</span></td>
                                            <th>{{ $av->available==0 ? __('site.available') :  __('site.not available')}}</th>
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table> {{-- end of table --}}

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

