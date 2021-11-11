{{ csrf_field() }}
<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.job_id')</label>
        <select id='jobchoose' name="job_id" required class='form-control'>
            <option value="">@lang('site.choose_job')</option>
            @foreach(\App\Models\job::where('status',1)->get() as $job)
                <option value="{{$job->id}}" @if(isset($row) && $row->job_id==$job->id) selected  @endif>{{'Company : ' . $job->employer->company_name .'     job : '.$job->title }}</option>
            @endforeach
        </select>
        @error('job_id')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.employee_id')</label>
        <select name="employee_id" required class='form-control'>
            @foreach(\App\Models\Employees::where('block',0)->get() as $emp)
                <option value="{{$emp->id}}" @if(isset($row) && $row->employee_id==$emp->id) selected  @endif>{{$emp->fullName}}</option>
            @endforeach
        </select>
        @error('employee_id')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>


<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.candat_applay_status')</label>
        <select name="candat_applay_status"  class='form-control'>
            <option value="">@lang('site.choose_candat_applay_status')</option>
            <option value="0" style='color:blue' @if(isset($row) && $row->candat_applay_status==0) selected @endif>@lang('site.reject')</option>
            <option value="1" style='color:red'  @if(isset($row) && $row->candat_applay_status==1) selected @endif>@lang('site.accept')</option>
            <option value="2" style='color:blue' @if(isset($row) && $row->candat_applay_status==2) selected @endif>@lang('site.shortlist')</option>
        </select>
        @error('candat_applay_status')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.avmeeting_id')</label>
        <select name="avmeeting_id"  class='form-control'>
        @if(isset($row))
            @php $avm=App\Models\Avmeeting::find($row->avmeeting_id); @endphp
                <option value="{{$avm->id}}" >{{$avm->time_from .' '.$avm->time_to}}</option>
        @endif    
        </select>
        @error('avmeeting_id')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.meeting_time_status')</label>
        <select name="meeting_time_status"  class='form-control'>
            <option value="">@lang('site.choose_meeting_time_status')</option> 
            <option value="0" style='color:blue' @if(isset($row) && $row->meeting_time_status==0) selected @endif>@lang('site.reject')</option>
            <option value="1" style='color:red'  @if(isset($row) && $row->meeting_time_status==1) selected @endif>@lang('site.accept')</option>
        </select>
        @error('meeting_time_status')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>


<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.candat_status')</label>
        <select name="candat_status"  class='form-control'>
            <option value="">@lang('site.choose_candat_status')</option> 
            <option value="0" style='color:blue' @if(isset($row) && $row->candat_status==0) selected @endif>@lang('site.reject')</option>
            <option value="1" style='color:red'  @if(isset($row) && $row->candat_status==1) selected @endif>@lang('site.accept')</option>
            <option value="2" style='color:blue' @if(isset($row) && $row->candat_status==2) selected @endif>@lang('site.underreview')</option>
        </select>
        @error('candat_status')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label>@lang('site.note')</label>
        <textarea class="form-control" name="note">{{ isset($row) ? $row->note : old('note') }}</textarea>
        @error('note')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
    @error('note')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
</div>
</div>