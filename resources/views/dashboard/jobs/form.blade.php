{{ csrf_field() }}
<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.employer_id')</label>
        <select name="employer_id" required class='form-control'>
            @foreach(\App\Models\Employer::where('active',1)->get() as $emp)
                <option value="{{$emp->id}}" @if(isset($row) && $row->employer_id==$emp->id) selected  @endif>{{$emp->company_name}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.job_field')</label>
        <select name="category_id" required class='form-control'>
            @foreach(\App\Models\Category::all() as $cat)
                <option value="{{$cat->id}}" @if(isset($row) && $row->category_id==$cat->id) selected  @endif>{{$cat->name}}</option>
            @endforeach
        </select>
    </div>
</div>

</div>
<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>job_specialize</label>
        <select name="job_specialize" required class='form-control'>
          
        </select>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.country_id')</label>
        <select name="country_id"  class='form-control'>
            <option>@lang('site.choose_country_id')</option> 
            @foreach(\App\Models\Country::all() as $cou)
                <option value="{{$cou->id}}" @if(isset($row) && $row->country_id==$cou->id) selected  @endif>{{$cou->name}}</option>
            @endforeach
        </select>
        @error('country_id')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div> 
</div>
</div>
<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.city_id')</label>
        <select name="city_id"  class='form-control'>
        
        </select>
        @error('city_id')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.choose_status')</label>
        <select name="status" required class='form-control'>
            <option value="1" style='color:blue' @if(isset($row) && $row->status==1) selected @endif>@lang('site.active')</option>
            <option value="0" style='color:red'  @if(isset($row) && $row->status==0) selected @endif>@lang('site.cancel')</option>
            <option value="2" style='color:blue' @if(isset($row) && $row->status==2) selected @endif>@lang('site.closed')</option>
        </select>
    </div>
</div>
</div>
<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.gender')</label>
        <select name="gender" class='form-control'>
            <option value="">@lang('site.choose_gender')</option>
            <option value="0" @if(isset($row) && $row->gender==0) selected  @endif>@lang('site.males')</option>
            <option value="1" @if(isset($row) && $row->gender==1) selected  @endif>@lang('site.females')</option>
            <option value="2" @if(isset($row) && $row->gender==2) selected  @endif>@lang('site.together')</option>
        </select> 
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.title1')</label>
        <input type="text" required class="form-control  @error('title') is-invalid @enderror" name="title"
            value="{{ isset($row) ? $row->title : old('title') }}">
        @error('title')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
</div>
<div class="row" style="margin: 0 !important;">


<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.salary')</label>
        <input type="text" class="form-control  @error('salary') is-invalid @enderror" name="salary"
            value="{{ isset($row) ? $row->salary : old('salary') }}">
        @error('salary')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>



<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.experience')</label>
        <input type="number"  class="form-control  @error('experience') is-invalid @enderror" name="experience"
            value="{{ isset($row) ? $row->experience : old('experience') }}">
        @error('experience')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
</div>
<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.qualification')</label>
        <input type="text"  class="form-control  @error('qualification') is-invalid @enderror" name="qualification"
            value="{{ isset($row) ? $row->qualification : old('qualification') }}">
        @error('qualification')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.interviewer_name')</label>
        <input type="text" required class="form-control  @error('interviewer_name') is-invalid @enderror" name="interviewer_name"
            value="{{ isset($row) ? $row->interviewer_name : old('interviewer_name') }}">
        @error('interviewer_name')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
</div>
<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.interviewer_role')</label>
        <input type="text" required class="form-control  @error('interviewer_role') is-invalid @enderror" name="interviewer_role"
            value="{{ isset($row) ? $row->interviewer_role : old('interviewer_role') }}">
        @error('interviewer_role')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.note')</label>
        <textarea class="form-control" name="note">{{ isset($row) ? $row->note : old('note') }}</textarea>
        @error('note')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
</div>
<div class="row" style="margin: 0 !important;">
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.details')</label>
        <textarea required class="form-control" name="details">{{ isset($row) ? $row->details : old('details') }}</textarea>
        @error('details')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
@php $count=0; @endphp
@isset($row)
    @php  if(isset($row)) {    $count=$row->avmeetings->where('available',1)->count(); } @endphp
@endisset
<div class="row" style="margin: 0 !important;">
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('site.meeting_date')</label>
        <input type="date"  class="form-control  @error('meeting_date') is-invalid @enderror" name="meeting_date"
            value="{{ isset($row) ? $row->meeting_date : old('meeting_date') }}" @if($count >0) disabled @else required @endif>
        @error('meeting_date')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label>@lang('site.meeting_time')</label>
        <input type="number"  class="form-control  @error('meeting_time') is-invalid @enderror" name="meeting_time"
            value="{{ isset($row) ? $row->meeting_time : old('meeting_time') }}"@if($count >0) disabled @else required @endif>
        @error('meeting_time')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror    </div>
</div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('site.meeting_from')</label>
        <input type="time"  class="form-control  @error('meeting_from') is-invalid @enderror" name="meeting_from"
            value="{{ isset($row) ? $row->meeting_from : old('meeting_from') }}" @if($count >0) disabled @else required @endif>
        @error('meeting_from')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror   
    </div> 
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('site.meeting_to')</label>
        <input type="time"  class="form-control  @error('meeting_to') is-invalid @enderror" name="meeting_to"
            value="{{ isset($row) ? $row->meeting_to : old('meeting_to') }}" @if($count >0) disabled @else required @endif>
        @error('meeting_to')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror 
       </div> 
    </div>
</div>
</div>

